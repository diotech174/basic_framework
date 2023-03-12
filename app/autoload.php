<?php
/*
Auto load
*/

error_reporting(E_ALL & ~E_NOTICE);

if(isset($_ENV["PHP_DISPLAY_ERRORS"])) {
    ini_set('display_errors', $_ENV["PHP_DISPLAY_ERRORS"]);
}
if(isset($_ENV["PHP_MAX_EXECUTION_TIME"])){
    ini_set('max_execution_time', $_ENV["PHP_MAX_EXECUTION_TIME"]);
}

require_once __DIR__."/Interface/BasicException.php";
require_once __DIR__."/Http/request.php";


$includes = [];

$libraries = glob(__DIR__."/Libraries/*.php");
$controllers = glob(__DIR__."/Http/Controllers/*.php");
$models = glob(__DIR__."/Models/*.php");

$includes[] = $libraries;
$includes[] = $controllers;
$includes[] = $models;

foreach ($includes as $folder) {
    if(is_array($folder))
    {
        foreach ($folder as $files) {
            require_once($files);
        }
    }
}

require_once __DIR__."/app.php";
require_once __DIR__."/../routers/Web.php";