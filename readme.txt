--------------------------------------------------------------------------------------
|                         Welcome tho basci Framework                                |
--------------------------------------------------------------------------------------

Install:

1. Run the command: php basic Install
2. Run the command: php basic serve
3. Open the URL: http://localhost:8000


###################################################################################################################

Create a new Controller

1. Run the command: php basic make:controller sample
2. Open the file: myproject/app/Http/Controllers/SampleController.php

###################################################################################################################

Create a new route

1. Open the file: myproject/routers/web.php
2. Include your controller class in top file:

use Http\Controllers\YourController;

3. Add a new line with the example code:

Example 1: $app->get("/hello", [YourController::class, "helloFunction"]);
Example 2: $app->get("/hello/{param1}/{param2}/...", [YourController::class, "helloFunction"]);
Example 3: $app->post("/save", [YourController::class, "saveFunction"]);

4. Access your new route, open the URL: http://localhost:xxxx/hello

###################################################################################################################

Create a new Model

1. Run the command: php basic make:model sample
2. Open the file: myproject/app/Models/ModelController.php