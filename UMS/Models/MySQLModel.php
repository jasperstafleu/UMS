<?php
namespace UMS\Models;

/**
 * Some default methods pertaining to a database model
 *
 * @method getId()
 *         integer getId()
 *         Retrieve the model's autoincrement key
 * @method setId()
 *         \UMS\Models\MySQLModel setId(integer $id)
 *         Set the model's autoincrement key
 * @method getCreated()
 *         \UMS\Models\DateTime getCreated()
 *         The \UMS\Models\DateTime value of the time at which the model was
 *         created
 * @method setCreated()
 *         \UMS\Models\MySQLModel setCreated(\UMS\Models\DateTime $datetime)
 *         Setter for the time at which the model was created
 * @method getChanged()
 *         \UMS\Models\DateTime getChanged()
 *         The \UMS\Models\DateTime value of most recent time at which the model
 *         was changed
 * @method setChanged()
 *         \UMS\Models\MySQLModel setChanged(\UMS\Models\DateTime $datetime)
 *         Setter for the changed time of the model
 * @author Jasper Stafleu
 */
abstract class MySQLModel extends Model implements \UMS\Interfaces\iDatabaseModel
{
    /**
     * Holder for the table name
     *
     * @var array
     */
    private static $_tables = array();

    /**
     * Holder for the PDO instance pointing to the MySQL database
     *
     * @var \PDO
     */
    private static $_pdo = null;

    /**
     * Identifier of the model
     *
     * @var integer
     */
    protected $_id = 0;

    /**
     * Date the model was created
     *
     * @var Date
     */
    protected $_created = null;

    /**
     * Date the model was changed last
     *
     * @var Date
     */
    protected $_changed = null;

    /**
     * Constructor: sets the created and changed fields
     */
    public function __construct(array $properties = array())
    {
        parent::__construct($properties);

        $dt = new DateTime();
        $this->_created = empty($this->_created) ? $dt : new DateTime($this->_created);
        $this->_changed = empty($this->_changed) ? $dt : new DateTime($this->_changed);
    } // __construct();

    /**
     * (non-PHPdoc)
     * @see \UMS\Interfaces\iDatabaseModel::save()
     * @throws PDOException
     */
    public function save()
    {
        return $this->id ? $this->_update() : $this->_insert();
    } // save();

    /**
     * (non-PHPdoc)
     * @see \UMS\Interfaces\iDatabaseModel::remove()
     */
    public function remove()
    {
        $this->_delete();
    } // remove();

    /**
     * Updates this model into the database
     *
     * @throws PDOException
     * @return \UMS\Models\MySQLModel
     */
    private function _insert()
    {
        if ( $this->id !== 0 ) {
            throw new \Exception('Can not insert an already existing ' . get_called_class() . ' ');
        }

        self::setTable();
        $table = self::getTable();
        $props = self::_getModelFields();

        $sql = "INSERT INTO `{$table}` (`" . implode('` ,`', $props) . "`)"
                . " VALUES (:" . implode(', :', $props) . ")";

        $stmt = self::getPDO()->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, get_called_class());

        foreach ( $props as $prop => $field ) {
            $stmt->bindValue(':' . $field, $this->$prop);
        } // foreach

        $stmt->execute();

        $stmt->closeCursor();
        $this->id = self::getPDO()->lastInsertId();

        return $this;
        // TODO: Implement _update
    } // _update();

    /**
     * Handles DELETE actions on the item
     */
    private function _delete()
    {
        if ( $this->id === 0 ) {
            throw new \Exception('Can not update a non-existing ' . get_called_class() . ' ');
        }

        self::setTable();
        $table = self::getTable();

        $sql = "DELETE FROM `{$table}` WHERE `id`={$this->id}";

        self::getPDO()->exec($sql);
    } // _delete();

    /**
     * Handles database insertion of a new object
     *
     * @throws PDOException
     * @return \UMS\Models\MySQLModel
     */
    private function _update()
    {
        if ( $this->id === 0 ) {
            throw new \Exception('Can not update a non-existing ' . get_called_class() . ' ');
        }

        $this->_changed = new DateTime();

        self::setTable();
        $table = self::getTable();
        $props = self::_getModelFields();

        $values = array();
        foreach ( $props as $field ) {
            $values []= " `{$field}` = :{$field}";
        } // foreach

        $sql = "UPDATE `{$table}` SET " . implode(', ', $values) . " WHERE id=:id";
        $stmt = self::getPDO()->prepare($sql);

        foreach ( $props as $prop => $field ) {
            $stmt->bindValue(':' . $field, $this->$prop);
        } // foreach

        $stmt->execute();
        $stmt->closeCursor();

        return $this;
    } // _update();

    /**
     * (non-PHPdoc)
     * @see \UMS\Interfaces\iDatabaseModel::get()
     */
    public static function get($id)
    {
        $sql = self::_getBaseSelect() . " WHERE id=:id LIMIT 1";

        $stmt = self::getPDO()->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $ret = $stmt->fetch(\PDO::FETCH_CLASS);
        $stmt->closeCursor();
        return $ret;
    } // get();

    /**
     * (non-PHPdoc)
     * @see \UMS\Interfaces\iDatabaseModel::getAll()
     */
    public static function getAll(array $params = array())
    {
        $sql = '';
        foreach ( $params as $prop => $value  ) {
            $sql .= " AND {$prop}=:{$prop}";
        } // foreach

        $sql = self::_getBaseSelect() . " WHERE " . substr($sql, strlen(" AND "));

        $stmt = self::getPDO()->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, get_called_class());

        foreach ( $params as $prop => $value ) {
            $stmt->bindValue(':' . $prop, $value);
        } // foreach

        $stmt->execute();
        $ret = $stmt->fetchAll();
        $stmt->closeCursor();

        return $ret;
    } // getAll();

    /**
     * Sets the table name for this class. If $db is provided, it is set to that
     * value. Otherwise, the table name is determined automatically (classname
     * without namespaces, in lowercase)
     *
     * @param string $table
     * @return string          Returns the previous table name
     */
    protected static function setTable($table = '')
    {
        $relClass = get_called_class();
        $oldTable = !empty(self::$_tables[$relClass]) ? self::$_tables[$relClass] : '';

        if ( !empty($table) ) {
            self::$_tables[$relClass] = $table;
        } else if ( empty(self::$_tables[$relClass]) ) {
            self::$_tables[$relClass] = strtolower((new \ReflectionClass(get_called_class()))->getShortName());
        }

        return $oldTable;
    } // setTable();

    /**
     * Returns the database table for this object. Will autogenerate it if
     * needed
     *
     * @return string
     */
    protected static function getTable()
    {
        self::setTable();
        return self::$_tables[get_called_class()];
    } // getTable();

    /**
     * Retrieves the pdo instance for extending classes. Creates it if needed
     *
     * @return PDO
     */
    protected static function getPDO()
    {
        if ( empty(self::$_pdo) ) {
            self::$_pdo = new \PDO(
                    'mysql:host=localhost;dbname=' . DB_NAME . ';charset=utf8',
                    DB_USER,
                    DB_PASS,
                    array(
                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                    )
            );
        }

        return self::$_pdo;
    } // getPDO();

    /**
     * Returns the SELECT statement used for filling the entities fields. It
     * should be concatenated with other strings (such as a WHERE clause)
     *
     * @return string
     */
    private static function _getBaseSelect()
    {
        $table = self::getTable();
        $props = self::_getModelFields(true);

        foreach ( $props as $prop => $field ) {
            $selProps []= " `{$table}`.`{$field}` AS {$prop}";
        } // foreach

        return "SELECT" . implode(',', $selProps) . " FROM `{$table}`";
    } // _getBaseSelect();

    /**
     * Returns the properties this model has. It is based on the protected
     * properties of the model, and the same fields (minus the starting
     * underscore) must be present in the database as well.
     *
     * @param boolean $dbFields    If set to true, returns the database form of
     *                             the property, instead of the actual form. In
     *                             the abstract form, this means removing the
     *                             first character (underscore)
     * @return array               The fields of this model. The keys will be
     *                             the model fields (with underscore), while the
     *                             values will be database form
     */
    protected static function _getModelFields()
    {
        $refl = new \ReflectionClass(get_called_class());
        $props = array();

        foreach ( $refl->getProperties(\ReflectionProperty::IS_PROTECTED) as $prop ) {
            $prop = $prop->getName();
            $props [$prop]= substr($prop, 1);
        } // foreach

        return $props;
    } // _getModelFields();

} // end class UMS\Models\MySQLModel