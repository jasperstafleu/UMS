<?php
namespace UMS\Controllers;

/**
 * Autoloader is a catch-all class to bundle the autoloaders.
 *
 * @author Jasper Stafleu
 */
class Autoloader
{
    /**
     * Enables the autoloader, which is a basic, PSR-0 autoloader
     */
    public static function enable($type = "PSR2")
    {
        if ( !method_exists(get_called_class(), $type) ) {
            throw new Exception("Unknown autoloader " . $type);
        }

        spl_autoload_register(array(get_called_class(), $type));
    } // enable();

    /**
     * The PSR-2 Autoloader
     *
     * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
     * @return boolean
     */
    public static function PSR2($class)
    {
        $cwd = getcwd();
        chdir(ROOTDIR);

        $namespaces = explode('\\', $class);
        $classname = explode('_', array_pop($namespaces));

        $psr2Path = array_merge($namespaces, $classname);

        $filename = implode(DIRECTORY_SEPARATOR, $psr2Path) . '.php';

        require_once($filename);
        chdir($cwd);

        return class_exists($class);
    } // PSR2();

} // end class UMS\Controllers\Autoloader