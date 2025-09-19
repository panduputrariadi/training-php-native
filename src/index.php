<?php
namespace PanduputragmailCom\PhpNative\Lib;

use PanduputragmailCom\PhpNative\lib\Logger;
use PanduputragmailCom\PhpNative\Lib\Routing;
use PanduputragmailCom\PhpNative\Routes\Api;

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/lib/Logger.php';

Logger::init(__DIR__ . '/../storage/logger/app.json.log');

$router = new Routing();

$router = Api::registerRoutes($router);

$router->run();