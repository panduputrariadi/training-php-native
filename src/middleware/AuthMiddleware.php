<?php
namespace PanduputragmailCom\PhpNative\middleware;

use PanduputragmailCom\PhpNative\lib\Middleware;
use PanduputragmailCom\PhpNative\lib\Response;

class AuthMiddleware implements Middleware
{
    public function handle($request, callable $next)
    {
        //getallheaders() -> ambil semua header dari request HTTP.
        $headers = getallheaders();
        
        //hanya static token
        if (!isset($headers['Authorization']) || $headers['Authorization'] !== 'Bearer secret123') {
            // response
            return Response::unauthorized('Unauthorized');
        }

        return $next($request);
    }
}
