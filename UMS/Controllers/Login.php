<?php
namespace UMS\Controllers;

use UMS\Models\LoginCookie;

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

                if ( !empty($_POST['remember']) ) {
                    $cookie = new \UMS\Models\LoginCookie(array(
                            'user' => $user->id
                    ));
                    $cookie->save();
                }

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
        if ( isset($_SESSION['LoginCookie']) ) {
            $_SESSION['LoginCookie']->remove();
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

    /**
     * Handler for automatic logging in. This particular method works via a
     * cookie, set during doLogin (based on the remember checkbox
     */
    public static function autoLogin()
    {
        if ( self::getUser() ) {
            // No need to do a autologin if already logged in
            return;
        }

        if ( $cookie = \UMS\Models\LoginCookie::getLoginCookie() ) {
            $_SESSION['User'] = \UMS\Models\User::get($cookie->getUser());
            $_SESSION['LoginCookie'] = $cookie;
        }
    } // autoLogin();

} // end class UMS\Controllers\Login