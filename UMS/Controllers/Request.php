<?php
namespace UMS\Controllers;

/**
 * Handler for the requests made through the browser
 *
 * @author Jasper Stafleu
 */
class Request
{
    /**
     * Basic method: every request should enter here, and will be redirected to
     * the relevant handler.
     *
     * @param string $slug
     */
    public static function handle($slug = '')
    {
        Login::autoLogin();

        $actualSlug = $slug;
        if ( $slug === '' ) {
            $slug = 'contextbased/user';
        }

        $explodedSlug = explode('/', $slug);
        $method = '_' . array_shift($explodedSlug);

        call_user_func_array(array(get_called_class(), $method), $explodedSlug);

        $_SESSION['previous_slug'] = $actualSlug;
    } // handle();

    /**
     * Handler for a login action
     */
    protected static function _login()
    {
        Login::doLogin();
        header('Location: /ums/' . (empty($_SESSION['previous_slug']) ? '' : $_SESSION['previous_slug']));
        exit;
    } // _login();

    /**
     * Handler for a logout action
     */
    protected static function _logout()
    {
        Login::doLogout();
        header('Location: /ums/' . (empty($_SESSION['previous_slug']) ? '' : $_SESSION['previous_slug']));
        exit;
    } // _logout();

    /**
     * Default type of handling: the $method is enacted upon the first of the
     * arguments, using the rest as arguments for the call. Eg: a slug in the
     * form of /update/user/2 becomes the call \UMS\Controllers\User::update(2)
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $what = __NAMESPACE__ . '\\' . ucfirst(array_shift($arguments));
        return call_user_func_array(array($what, substr($method, 1)), $arguments);
    } // __callStatic();

} // end class UMS\Controllers\Request