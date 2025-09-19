<?php
namespace PanduputragmailCom\PhpNative\Lib;

use PanduputragmailCom\PhpNative\Lib\Routing;
use PanduputragmailCom\PhpNative\Routes\Api;

require __DIR__.'/../vendor/autoload.php';

$router = new Routing();

$router = Api::registerRoutes($router);

$router->run();