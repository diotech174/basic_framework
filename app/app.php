<?php
namespace App;

use Exception;
use Http\Request\Request;
use App\BasicExceptions\BasicException;

define("ERROR_DEFAULT_ROUTER_NOT_FOUND", "<h2>Default router not found!<h2><hr>");
define("ERROR_URL_NOT_FOUND", "<h2>Erro 404 - Page not found!</h2><hr>");
define("ERROR_METHOD_GET_NOT_ALLOWED", "<h2>Method get not allowed!</h2><hr>");
define("ERROR_METHOD_POST_NOT_ALLOWED", "<h2>Method post not allowed!</h2><hr>");
define("APP_LOGO", base64_encode(file_get_contents(__DIR__."/Interface/Files/images/favicon.png")));
define("APP_BOOTSTRAP_CSS", base64_encode(file_get_contents(__DIR__."/Interface/Files/css/bootstrap.min.css")));
define("APP_BOOTSTRAP_JS", base64_encode(file_get_contents(__DIR__."/Interface/Files/js/bootstrap.min.js")));
define("APP_DEBUG_JS", base64_encode(file_get_contents(__DIR__."/Interface/Files/js/debug.js")));
define("APP_DEBUG_CSS", base64_encode(file_get_contents(__DIR__."/Interface/Files/css/debug.css")));

class App
{
    private $url;
    private $routes = [];
    private $routerNow;

    public function __construct()
    {
        $url = $_SERVER["REQUEST_URI"];
        $this->url = isset($_ENV["BASE_URL"]) ? str_replace($_ENV["BASE_URL"], "", $url) : $url;
    }

    private function extractParameters($string) {
        $default = '/\{([^}]*)\}/';
        preg_match_all($default, $string, $occurrences);
        return $occurrences[1];
    }
    
    private function setRouter($path, $controller, $method)
    {
        try {
            $params = $this->extractParameters($path);
            $router = $path;

            $this->routes[$router] = [
                "method" => $method,
                "class" => $controller[0],
                "function" => $controller[1],
                "params" => $params
            ];
        } catch (Exception $e) {
            throw new BasicException($e->getMessage());
        }
    }

    public function get($path, $controller)
    {
        $this->setRouter($path, $controller, "GET");
    }

    public function post($path, $controller)
    {
        $this->setRouter($path, $controller, "POST");
    }

    private function execRouter($class, $function, $params=null)
    {
        try {

            if ($params === null || empty($params)) {
                $class->$function();
            } else {
                $class->$function($params);
            }
            
            $this->startDebug();

        } catch (Exception $e) {
            throw new BasicException($e->getMessage());
        }
    }

    private function checkMethod($router)
    {
        if (!empty($_POST)&& $router->method === "GET") {
            throw new BasicException(ERROR_METHOD_POST_NOT_ALLOWED);
        } elseif (empty($_POST) && $router->method === "POST") {
            throw new BasicException(ERROR_METHOD_GET_NOT_ALLOWED);
        }
    }

    private function render()
    {
        try {
            
            $urlParams = array_filter(explode("/", $this->url));

            if (empty($urlParams)) {
                $router = (object)$this->routes["/"];
                $this->routerNow = $router;

                $this->checkMethod($router);

                $class = new $router->class();
                $function = $router->function;

                $request = new Request();
                $request->setQueryParam($_GET);

                $class->$function($request);
                $this->startDebug();

            } else {

                $routerExistis = false;

                foreach ($this->routes as $route_pattern => $route) {
                    $route_parts_pattern = preg_replace('/\{.*?\}/', '([^/]+)', $route_pattern);
                    $route_pattern_regex = '@^' . $route_parts_pattern . '$@i';

                    if (preg_match($route_pattern_regex, $this->url, $matches)) {
                        array_shift($matches);

                        $this->routerNow = $route;

                        $controller = (object)$route;
                        $class = new ($controller->class)();
                        $function = $controller->function;

                        $params = $controller->params;

                        if (empty($params)) {
                            $this->execRouter($class, $function);
                        } else {

                            $pathVariables = [];

                            foreach ($params as $i => $param) {
                                $pathVariables[$param] = $matches[$i];
                            }

                            $request = new Request();
                            $request->setPathVariable($pathVariables);
                            $request->setQueryParam($_GET);
                            $request->setPost($_POST);
        
                            $this->execRouter($class, $function, $request);
                        }

                        $routerExistis = true;
                        break;
                    }
                }

                if (!$routerExistis) {
                    throw new BasicException(ERROR_URL_NOT_FOUND);
                }
            }
        } catch (Exception $e) {
            throw new BasicException($e->getMessage());
        }
    }

    private function startDebug()
    {
        if (!isset($_ENV["APP_DEBUG"]) || $_ENV["APP_DEBUG"] === "true") {
            echo "\n\n<!-- APP DEBUG -->";
            echo "\n<script>";

            echo "\n\tlet css = document.createElement('link');";
            echo "\n\tcss.setAttribute('href', 'data:text/css;base64,".APP_DEBUG_CSS."');";
            echo "\n\tlet js = document.createElement('script');";
            echo "\n\tjs.setAttribute('src', 'data:text/javascript;base64,".APP_DEBUG_JS."');";

            echo "\n\tlet ctx = document.body;";
            echo "\n\tctx.appendChild(css);";
            echo "\n\tctx.appendChild(js);";

            
            $dataDebug = [
                "router" => $this->routerNow,
                "dataViwer" => DATATWIG
            ];

            echo "\n\tsetTimeout(() => {";

            echo "\n\t\tsetData('".base64_encode(json_encode($dataDebug))."');";
            echo "\n\t}, 500);";
            
            echo "\n</script>";
        }
    }

    public function run()
    {
        try {
            if (!isset($this->routes["/"])) {
                throw new BasicException(ERROR_DEFAULT_ROUTER_NOT_FOUND);
            } else {
                $this->render();
            }
        } catch (Exception $e) {
            throw new BasicException($e->getMessage());
        }
    }
}