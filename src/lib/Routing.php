<?php

namespace PanduputragmailCom\PhpNative\Lib;

class Routing
{
    private $routes = [];

    public function add(string $method, string $path, $callback)
    {
        $this->routes[] = [
            'method'   => $method,
            'path'     => $path,
            'callback' => $callback
        ];
    }

    // old function without params
    // public function run()
    // {
    //     $method = $_SERVER['REQUEST_METHOD'];
    //     $uri    = $_SERVER['REQUEST_URI'];

    //     foreach ($this->routes as $route) {
    //         if ($route['method'] != $method) {
    //             continue;
    //         }

    //         if ($route['path'] == $uri) {
    //             return call_user_func($route['callback']);
    //         }
    //     }

    //     header('HTTP/1.1 404 Not Found');
    //     die('404 Not Found');
    // }
    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] != $method) {
                continue;
            }
            
            $regexPattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) {
                return '([^/]+)';
            }, $route['path']);

            if (preg_match("#^{$regexPattern}$#", $uri, $params)) {
                array_shift($params);

                if (is_callable($route['callback'])) {
                    return call_user_func_array($route['callback'], $params);
                } else {
                    list($controller, $method) = $route['callback'];
                    $instance = new $controller();
                    echo $instance->$method(...$params);
                    // return $instance->$method(...$params);
                }
            }
        }
    }
    
}