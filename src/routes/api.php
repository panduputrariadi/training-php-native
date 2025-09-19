<?php
    namespace PanduputragmailCom\PhpNative\routes;

use PanduputragmailCom\PhpNative\Controller\Controller;
use PanduputragmailCom\PhpNative\Controller\DummyDataController;
use PanduputragmailCom\PhpNative\Lib\Routing;

    class Api{
        public static function registerRoutes(Routing $router)
        {
            $router->add('GET', '/', function() {
                return 'Hello world';
            });

            $router->add('GET', '/connect-db', [Controller::class, 'ConnectDB']);
            $router->add('GET', '/get-dummy-data', [DummyDataController::class, 'GetDataDummy']);
            $router->add('POST', '/store-dummy-data', [DummyDataController::class, 'store']);
            
            return $router;
        }
    }
?>