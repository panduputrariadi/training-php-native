<?php

namespace PanduputragmailCom\PhpNative\lib;
use PanduputragmailCom\PhpNative\enum\HttpStatus;
class Response
{
    public static function json($data = [], string $message = '', int|HttpStatus $statusCode = 200)
    {
        // Jika enum, ambil valuenya
        $code = $statusCode instanceof HttpStatus ? $statusCode->value : $statusCode;

        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode([
            'status'  => $code,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    public static function success($data = [], string $message = 'Success')
    {
        self::json($data, $message, HttpStatus::OK);
    }

    public static function notFound(string $message = 'Not Found')
    {
        self::json([], $message, HttpStatus::NOT_FOUND);
    }

    public static function badRequest($data = [], string $message = 'Bad Request')
    {
        self::json($data, $message, HttpStatus::BAD_REQUEST);
    }

    public static function unauthorized(string $message = 'Unauthorized')
    {
        self::json([], $message, HttpStatus::UNAUTHORIZED);
    }

    public static function serverError(string $message = 'Internal Server Error')
    {
        self::json([], $message, HttpStatus::INTERNAL_ERROR);
    }

    public static function created($data = [], string $message = 'Success Created')
    {
        self::json($data, $message, HttpStatus::CREATED);
    }
}