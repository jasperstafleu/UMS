<?php
namespace UMS\Controllers;

/**
 * User controller
 *
 * @author Jasper Stafleu
 */
class User
{
    /**
     * Creates the User, using $_POST as properties of the user
     *
     * @param array $properties
     */
    public static function create()
    {
        $user = new \UMS\Models\User($_POST);
        $user->save();
    } // create();

} // end class UMS\Controllers\User