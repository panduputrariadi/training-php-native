<?php
namespace PanduputragmailCom\PhpNative\Lib;
use PanduputragmailCom\PhpNative\Controller\Controller;
use PanduputragmailCom\PhpNative\Controller\DummyDataController;

require __DIR__.'/../vendor/autoload.php';

$router = new Routing();
$router->add('GET', '/', function() {
    return 'Hello world';
});

// $router->add('GET', '/connect-db', function() {
//     $controller = new Controller();
//     $connection = $controller->ConnectDB();
    
//     // Jika ingin menampilkan pesan sukses
//     return $connection;
// });
$router->add('GET', '/connect-db', [Controller::class, 'ConnectDB']);
$router->add('GET', '/get-dummy-data', [DummyDataController::class, 'GetDataDummy']);
$router->add('POST', '/store-dummy-data', [DummyDataController::class, 'store']);

$router->run();

// echo "Hello world";