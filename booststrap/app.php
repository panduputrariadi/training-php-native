<?php

use PanduputragmailCom\PhpNative\lib\Logger;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/lib/Logger.php';

Logger::init(__DIR__ . '/../storage/logger/app.log');