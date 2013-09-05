<?php
namespace UMS\Interfaces;

/**
 * Interface for a model. These are the methods any model must implement
 *
 * @author Jasper Stafleu
 */
interface iDatabaseModel
{
    /**
     * Save this instance of the model to a non-session based storing medium
     * (ie: database)
     *
     * @return iDatabaseModel
     */
    public function save();

    /**
     * Remove the model from the Database. Dependent on the actual model, this
     * might be simply a DELETE action, setting a deleted flag or include
     * creating a backup
     */
    public function remove();

    /**
     * Retrieve the model with $id from the database
     *
     * @param integer $id
     */
    public static function get($id);

    /**
     * Retrieves all models of this type that have each of its valid fields set
     * to the values passed in $params
     *
     * @param array $params
     * @return array of iDatabaseModels
     */
    public static function getAll(array $params = array());

} // end interface UMS\Interfaces\iDatabaseModel
