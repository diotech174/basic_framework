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
        $this->url = $_SERVER["REQUEST_URI"];
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
            $paramsPath = explode("/", $path);
            $router = isset($paramsPath[1]) ? "/". $paramsPath[1] : "/";

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
        if(count($_POST) > 0 && $router->method === "GET") {
            throw new BasicException(ERROR_METHOD_POST_NOT_ALLOWED);
        } else if (empty($_POST) && $router->method === "POST")
        {
            throw new BasicException(ERROR_METHOD_GET_NOT_ALLOWED);
        }
    }

    private function render()
    {
        try {
            $urlParams = array_filter(explode("/", $this->url));

            if(empty($urlParams) || substr($urlParams[1], 0, 1) === "?")
            {
                $router = (object)$this->routes["/"];
                $this->routerNow = $router;

                $this->checkMethod($router);

                $class = new $router->class();
                $function = $router->function;

                $request = new Request();
                $request->setQueryParam($_GET);

                $class->$function($request);
                $this->startDebug();

            } elseif (isset($this->routes["/".$urlParams[1]])) {

                $router = (object)$this->routes["/".$urlParams[1]];
                $this->routerNow = $router;

                $this->checkMethod($router);

                $class = new $router->class();
                $function = $router->function;
                $params = $router->params;

                $paramsUrl = array_filter(explode("/", str_replace("/".$urlParams[1], "", $this->url)));

                if (empty($params)) {
                    $this->execRouter($class, $function);
                } elseif(count($paramsUrl) === count($params)) {
                    $pathVariables = [];

                    foreach($params as $key => $param) {

                        if (isset($paramsUrl[$key+1])) {
                            $pathVariables[$param] = $urlParams[$key+1];
                        }
                    }

                    $request = new Request();
                    $request->setPathVariable($pathVariables);
                    $request->setQueryParam($_GET);
                    $request->setPost($_POST);

                    $this->execRouter($class, $function, $request);

                }  else {
                    throw new BasicException(ERROR_URL_NOT_FOUND);
                }

            }  else {
                throw new BasicException(ERROR_URL_NOT_FOUND);
            }
        } catch (Exception $e) {
            throw new BasicException($e->getMessage());
        }
    }

    private function startDebug()
    {
        if(!isset($_ENV["APP_DEBUG"]) || $_ENV["APP_DEBUG"] === "true")
        {
            //$css = "<link href='data:text/css;base64,".APP_DEBUG_JS."'>";
            //$js = "<script src='data:text/javascript;base64,".APP_DEBUG_JS."'></script>";

            echo "<script>";

            echo "let css = document.createElement('link');";
            echo "css.setAttribute('href', 'data:text/css;base64,".APP_DEBUG_JS."');";
            echo "let js = document.createElement('script');";
            echo "js.setAttribute('src', 'data:text/javascript;base64,".APP_DEBUG_JS."');";

            echo "let ctx = document.body;";
            echo "ctx.appendChild(css);";
            echo "ctx.appendChild(js);";

            $dataDebug = [
                "router" => $this->routerNow,
                "dataViwer" => DATATWIG
            ];
            echo "setTimeout(() => {";
            echo "setData('".base64_encode(json_encode($dataDebug))."');";
            echo "}, 500);";
            echo "</script>";
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