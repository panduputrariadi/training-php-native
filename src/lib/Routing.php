<?php

namespace PanduputragmailCom\PhpNative\Lib;

use PanduputragmailCom\PhpNative\enum\HttpStatus;
use PanduputragmailCom\PhpNative\lib\Response; 

class Routing
{
    private $routes = [];

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
                    if (is_callable($route['callback'])) {
                        return call_user_func_array($route['callback'], $params);
                    } else {
                        list($controller, $method) = $route['callback'];
                        $instance = new $controller();
                        return $instance->$method(...$params); 
                    }
                };

                foreach (array_reverse($route['middlewares']) as $middleware) {
                    $instance = new $middleware();
                    $next = $callback;
                    $callback = function($req) use ($instance, $next) {
                        $result = $instance->handle($req, $next);
                        return $result;
                    };
                }

                try {
                    $result = $callback($request);

                    if (is_array($result) && isset($result['status'])) {
                        Response::json(
                            $result['data'] ?? [],
                            $result['message'] ?? '',
                            $result['status'] ?? HttpStatus::OK
                        );
                    } else {
                        Response::json($result, 'OK', HttpStatus::OK);
                    }
                } catch (\Throwable $e) {
                    return Response::json(
                        [],
                        $e->getMessage(),
                        HttpStatus::INTERNAL_ERROR
                    );
                }

                return;
            }
        }
        Response::json([], '404 Not Found', HttpStatus::NOT_FOUND);
    }
}