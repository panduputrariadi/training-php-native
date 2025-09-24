<?php
require_once __DIR__ . '/vendor/autoload.php';

use PanduputragmailCom\PhpNative\Model\DummyData;
use PanduputragmailCom\PhpNative\model\queries\DummyDataQueries;

$model = new DummyData();
$queries1 = new DummyDataQueries($model);
$queries2 = new DummyDataQueries($model);

echo "Connection in queries1: " . spl_object_hash($queries1->getConnection()) . "\n";
echo "Connection in queries2: " . spl_object_hash($queries2->getConnection()) . "\n";

$qb1 = new \PanduputragmailCom\PhpNative\lib\QueryBuilder($queries1->getConnection());
$qb2 = new \PanduputragmailCom\PhpNative\lib\QueryBuilder($queries2->getConnection());

echo "Connection in qb1: " . spl_object_hash($qb1->getConnectionForDebug()) . "\n";
echo "Connection in qb2: " . spl_object_hash($qb2->getConnectionForDebug()) . "\n";

$isSame = $queries1->getConnection() === $queries2->getConnection();
echo "Is the connection used between you and me the same? " . ($isSame ? "YES" : "NO") . "\n";