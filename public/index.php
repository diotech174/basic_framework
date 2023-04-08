<?php
require_once __DIR__."/../vendor/autoload.php";

if (file_exists(__DIR__."/../.env")) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/..");
    $dotenv->load();
}

require_once __DIR__."/../app/autoload.php";

$app->run();