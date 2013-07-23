<?php
namespace UMS\Models;

/**
 * Some default methods pertaining to a database model
 *
 * @author Jasper Stafleu
 */
abstract class DatabaseModel extends Model implements \UMS\Interfaces\iDatabaseModel
{
    /**
     * (non-PHPdoc)
     * @see \UMS\Interfaces\iDatabaseModel::save()
     */
    public function save()
    {
        header('Content-type: text/txt');
        var_dump($this);
        // TODO: implement
    } // save();

    /**
     * (non-PHPdoc)
     * @see \UMS\Interfaces\iDatabaseModel::get()
     */
    public function get($id)
    {

    } // get();

    /**
     * (non-PHPdoc)
     * @see \UMS\Interfaces\iDatabaseModel::getAll()
     */
    public function getAll(array $params = array())
    {

    } // getAll();

} // end class DatabaseModel