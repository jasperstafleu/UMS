<?php
// -----------------------------------------------------------------------------
// DO NOT add sensitive data in this file; use config.local.php instead, which
// is .gitignored. It MUST at least contain
// - Database information (DB_NAME, DB_USER, DB_PASS)
// -----------------------------------------------------------------------------

// set error reporting. TODO: Turn this off for production environment
ini_set("display_errors", true);
error_reporting(E_ALL);

// PHP minimum version compare
// Highest requirement: 5.3.7 for "safe" crypt method ($2y blowfish)
if ( version_compare(PHP_VERSION, "5.3.7") < 0 ) {
    throw new Exception("PHP version " . PHP_VERSION . " is insufficient, please upgrade");
}

// Set the ROOTDIR constant to the parent's parent directory
define('ROOTDIR', realpath(getcwd() . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR);
define('TMPLDIR', ROOTDIR . 'templates' . DIRECTORY_SEPARATOR);

// Set the default timezone to Europe/Amsterdam
date_default_timezone_set("Europe/Amsterdam");

// Enable autoloading
require_once ROOTDIR . 'UMS/Controllers/Autoloader.php';

// Set autoloaders
\UMS\Controllers\Autoloader::enable('PSR2');

// ensure local config setting is done after any requirements for local settings,
// but before anything local setting is not dependent upon
require_once "config.local.php";

// Create the session
session_name('UMS');
session_start();
