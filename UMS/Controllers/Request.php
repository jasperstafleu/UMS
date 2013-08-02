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
        $actualSlug = $slug;
        if ( $slug === '' ) {
            $slug = 'contextbased/user';
        }

        $explodedSlug = explode('/', $slug);
        $method = '_' . array_shift($explodedSlug);

        call_user_func_array([get_called_class(), $method], $explodedSlug);

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
     * Create a new item
     *
     * @return mixed
     */
    protected static function _contextbased()
    {
        $arguments = func_get_args();
        $what = __NAMESPACE__ . '\\' . ucfirst(array_shift($arguments));
        return call_user_func_array([$what, 'contextbased'], $arguments);
    } // _create();

} // end class UMS\Controllers\Request