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

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = $_SERVER['REQUEST_URI'];

        foreach ($this->routes as $route) {
            if ($route['method'] != $method) {
                continue;
            }

            if ($route['path'] == $uri) {
                return call_user_func($route['callback']);
            }
        }

        header('HTTP/1.1 404 Not Found');
        die('404 Not Found');
    }
}