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
        if ( $slug === '' ) {
            $slug = 'create/user';
        }

        $explodedSlug = explode('/', $slug);
        $method = '_' . array_shift($explodedSlug);

        call_user_func_array([get_called_class(), $method], $explodedSlug);
    } // handle();

    /**
     * Create a new item
     *
     * @return mixed
     */
    protected static function _create()
    {
        $arguments = func_get_args();
        $what = __NAMESPACE__ . '\\' . ucfirst(array_shift($arguments));
        return call_user_func_array([$what, 'create'], $arguments);
    } // _create();

} // end class UMS\Controllers\Request