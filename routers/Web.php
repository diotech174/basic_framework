<?php
use App\App;
use Http\Controllers\WelcomeController;

$app = new App();

$app->get("/", [WelcomeController::class, "Index"]);

$app->get("/teste/page/{id}", [WelcomeController::class, "Index"]);