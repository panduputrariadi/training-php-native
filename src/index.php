<?php
namespace PanduputragmailCom\PhpNative\Lib;
use PanduputragmailCom\PhpNative\Controller\Controller;

require __DIR__.'/../vendor/autoload.php';

$router = new Routing();
$router->add('GET', '/', function() {
    return 'Hello world';
});

$router->add('GET', '/connect-db', function() {
    $controller = new Controller();
    $connection = $controller->ConnectDB();
    
    // Jika ingin menampilkan pesan sukses
    return $connection;
});

$router->run();

// echo "Hello world";