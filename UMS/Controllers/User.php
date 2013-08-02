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
     */
    public static function create()
    {
        echo new \UMS\Views\Standard('standard.phtml', $_POST);
    } // create();

    /**
     * Shows a listing of all users in the database
     */
    public static function listing()
    {
        echo new \UMS\Views\Standard('listing.phtml');
    } // listing();

    /**
     * Shows a listing of all users in the database
     */
    public static function update()
    {
        echo new \UMS\Views\Standard('standard.phtml', Login::getUser());
    } // listing();

    /**
     * Does context based controlling, which might mean
     * - Showing the create user form (if no user is logged in)
     * - Showing the logged in user form (for normal users)
     * - Showing a listing of available users (for admins)
     */
    public static function contextbased($step = 'init')
    {
        $user = Login::getUser();

        switch ( true ) {
            default :
            case !$user :
                return self::create();
            case $user->isAdmin :
                return self::listing();
            case !!$user :
                return self::update();
        } // switch

    } // contextbased ();

} // end class UMS\Controllers\User