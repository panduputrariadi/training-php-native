<?php
namespace PanduputragmailCom\PhpNative\lib;

interface Middleware
{
    public function handle($request, callable $next);
}
