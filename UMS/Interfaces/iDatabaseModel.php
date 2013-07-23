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
     * Retrieve the model with $id from the database
     *
     * @param integer $id
     */
    public function get($id);

    /**
     * Retrieves all models of this type that have each of its valid fields set
     * to the values passed in $params
     *
     * @param array $params
     * @return array of iDatabaseModels
     */
    public function getAll(array $params = array());

} // end interface \UMS\Interfaces\iDatabaseModel
