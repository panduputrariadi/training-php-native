<?php
    namespace PanduputragmailCom\PhpNative\routes;

use PanduputragmailCom\PhpNative\Controller\Controller;
use PanduputragmailCom\PhpNative\Controller\DummyDataController;
use PanduputragmailCom\PhpNative\Lib\Routing;
use PanduputragmailCom\PhpNative\middleware\AuthMiddleware;

    class Api{
        public static function registerRoutes(Routing $router)
        {
            $router->add('GET', '/', function() {
                return 'Hello world';
            });

            $router->add('GET', '/connect-db', [Controller::class, 'ConnectDB']);

            // without middleware
            // $router->add('GET', '/get-dummy-data', [DummyDataController::class, 'GetDataDummy']);

            //with middleware
            $router->add('GET', '/get-dummy-data', [DummyDataController::class, 'GetDataDummy'], [
                AuthMiddleware::class
            ]);
            $router->add('POST', '/store-dummy-data', [DummyDataController::class, 'store']);
            
            return $router;
        }
    }
?>