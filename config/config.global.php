<?php
// DO NOT add sensitive data in this file; use config.local.php instead, which
// is .gitignored. It MUST at least contain
// - Database information (server, name, password, etc)

ini_set("display_errors", true);
error_reporting(E_ALL);

if ( version_compare(PHP_VERSION, "5.3") < 0 ) {
    throw new Exception("PHP version " . PHP_VERSION . " is insufficient, please upgrade");
}

define('ROOTDIR', realpath(getcwd() . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR);

require_once ROOTDIR . 'UMS/Controllers/Autoloader.php';
\UMS\Controllers\Autoloader::enable('PSR2');

require_once "config.local.php";
