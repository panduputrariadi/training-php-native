<?php

namespace PanduputragmailCom\PhpNative\lib;

class Response {
    public static function json($data = [], string $message = '', int $statusCode = 200){
        http_response_code($statusCode);
        header('Content-Type: application/json');

        echo json_encode([
            'status'  => $statusCode,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    public static function success($data = [], string $message = 'Success'){
        self::json($data, $message, 200);
    }

    public static function notFound(string $message = 'Not Found'){
        self::json([], $message, 404);
    }

    public static function badRequest(string $message = 'Bad Request'){
        self::json([], $message, 400);
    }

    public static function serverError(string $message = 'Internal Server Error'){
        self::json([], $message, 500);
    }
    
    public static function created($data = [],string $message = 'Success Created'){
        self::json($data, $message, 201);
    }
}