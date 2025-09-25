<?php
namespace PanduputragmailCom\PhpNative\middleware;

use PanduputragmailCom\PhpNative\lib\Middleware;
use PanduputragmailCom\PhpNative\lib\Response;

class AuthMiddleware implements Middleware
{
    public function handle($request, callable $next)
    {
        $headers = getallheaders();
        
        if (!isset($headers['Authorization']) || $headers['Authorization'] !== 'Bearer secret123') {
            return Response::unauthorized('Unauthorized');
        }

        return $next($request);
    }
}
