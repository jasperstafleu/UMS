<?php
if ( version_compare(PHP_VERSION, "5.3") < 0 ) {
    throw new Exception("PHP version " . PHP_VERSION . " is insufficient, please upgrade");
}

require_once "config.local.php";