<?php

namespace PanduputragmailCom\PhpNative\lib;

class Logger
{
    protected static $logFile;

    public static function init($path = __DIR__ . '/../storage/logger/app.log')
    {
        self::$logFile = $path;
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    protected static function write($level, $message, $context = [])
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level'     => strtoupper($level),
            'message'   => $message,
            'context'   => $context,
        ];

        $json = json_encode($logEntry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        file_put_contents('php://stdout', $json . PHP_EOL);

        file_put_contents(self::$logFile, $json . PHP_EOL, FILE_APPEND);
        if (php_sapi_name() !== 'cli') {
            header('Content-Type: application/json');
            echo $json;
        }
    }

    public static function info($message, $context = [])
    {
        self::write('info', $message, $context);
    }
    public static function warning($message, $context = [])
    {
        self::write('warning', $message, $context);
    }
    public static function error($message, $context = [])
    {
        self::write('error', $message, $context);
    }

    public static function handleException($e)
    {
        self::error("Uncaught Exception", [
            'type'    => get_class($e),
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTrace(),
        ]);
    }

    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        self::error("PHP Error", [
            'errno'   => $errno,
            'message' => $errstr,
            'file'    => $errfile,
            'line'    => $errline,
        ]);
        return true;
    }

    public static function handleShutdown()
    {
        $error = error_get_last();
        if ($error !== null) {
            self::error("Fatal Error", $error);
        }
    }
}
