<?php
use App\App;
use Http\Controllers\WelcomeController;

$app = new App();

$app->get("/", [WelcomeController::class, "Index"]);