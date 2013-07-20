<?php
require "../config/config.global.php";
header('content-type: text/txt');
print_r(array_intersect_key($_SERVER,['slug'=>'','REDIRECT_slug'=>'']));