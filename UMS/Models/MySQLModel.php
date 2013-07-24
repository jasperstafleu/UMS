<?php
namespace UMS\Models;

/**
 * Some default methods pertaining to a database model
 *
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
     */
    public function save()
    {
        self::setTable();

        header('Content-type: text/txt');
        print_r($this);
        // TODO: implement
    } // save();

    /**
     * (non-PHPdoc)
     * @see \UMS\Interfaces\iDatabaseModel::get()
     */
    public static function get($id)
    {
        $refl = new \ReflectionClass(get_called_class());
        $props = array();
        $table = self::getTable();

        foreach ( $refl->getProperties(\ReflectionProperty::IS_PROTECTED) as $prop ) {
            $prop = $prop->getName();
            $props []= "`{$table}`.`" . substr($prop, 1) . "` AS {$prop}";
        } // foreach

        $sql = "SELECT " . implode(',', $props) . " FROM {$table} WHERE id=:id LIMIT 1";

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
        // TODO: ensure datetimes use CONVERT_TZ (see http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function%5Fconvert-tz)
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

} // end class MySQLModel