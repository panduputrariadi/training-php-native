<?php

namespace PanduputragmailCom\PhpNative\Lib;

class Routing
{
    private $routes = [];

    // menambahkan array dari middleware
    public function add(string $method, string $path, $callback, array $middlewares = []){
        $this->routes[] = [
            'method'      => $method,
            'path'        => $path,
            'callback'    => $callback,
            'middlewares' => $middlewares
        ];
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // loop semua route
        foreach ($this->routes as $route) {
            if ($route['method'] != $method) {
                continue;
            }

            $regexPattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) {
                return '([^/]+)';
            }, $route['path']);

            if (preg_match("#^{$regexPattern}$#", $uri, $params)) {
                array_shift($params);

                $request = [
                    'method' => $method,
                    'uri'    => $uri,
                    'params' => $params,
                ];

                $callback = function($req) use ($route, $params) {
                    // kalau callbacknya sebuah function maka jalankan, jika tidak maka jalankan controller
                    if (is_callable($route['callback'])) {
                        return call_user_func_array($route['callback'], $params);
                    } else {
                        list($controller, $method) = $route['callback'];
                        $instance = new $controller();
                        // return $instance->$method(...$params);
                        echo $instance->$method(...$params);
                    }
                };

                // jalankan middleware jika menambahkan middleware
                foreach (array_reverse($route['middlewares']) as $middleware) {
                    $instance = new $middleware();
                    $next = $callback;
                    $callback = function($req) use ($instance, $next) {
                        return $instance->handle($req, $next);
                    };
                }

                return $callback($request);
            }
        }
        
        http_response_code(404);
        echo "404 Not Found";
    }
}