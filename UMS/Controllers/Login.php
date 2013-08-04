<?php
namespace UMS\Controllers;

class Login
{
    /**
     * Performs a login using the POST variables
     */
    public static function doLogin()
    {
        $_SESSION['User'] = null;

        $params = array_intersect_key($_POST, array('email' => 0));
        $potentialUsers = \UMS\Models\User::getAll($params);
        foreach ( $potentialUsers as $user ) {
            if ( $user->isCorrectPass($_POST['pass']) ) {
                $_SESSION['User'] = $user;
                return;
            }
        } // foreach
    } // doLogin();

    /**
     * Performs a login using the POST variables
     */
    public static function doLogout()
    {
        if ( isset($_SESSION['User']) ) {
            unset($_SESSION['User']);
        }
    } // doLogout();

    /**
     * Returns the currently logged in user
     * @return \UMS\Models\User
     */
    public static function getUser()
    {
        return isset($_SESSION['User']) ? $_SESSION['User'] : null;
    } // getUser();

} // end class UMS\Controllers\Login