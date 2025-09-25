<?php

namespace PanduputragmailCom\PhpNative\enum;

enum HttpStatus: int
{
    case OK                  = 200;
    case CREATED             = 201;
    case NO_CONTENT          = 204;

    case BAD_REQUEST         = 400;
    case UNAUTHORIZED        = 401;
    case FORBIDDEN           = 403;
    case NOT_FOUND           = 404;

    case INTERNAL_ERROR      = 500;
    case NOT_IMPLEMENTED     = 501;
    case SERVICE_UNAVAILABLE = 503;
    
    public function description(): string
    {
        return match($this) {
            self::OK                  => 'OK',
            self::CREATED             => 'Created',
            self::NO_CONTENT          => 'No Content',
            self::BAD_REQUEST         => 'Bad Request',
            self::UNAUTHORIZED        => 'Unauthorized',
            self::FORBIDDEN           => 'Forbidden',
            self::NOT_FOUND           => 'Not Found',
            self::INTERNAL_ERROR      => 'Internal Server Error',
            self::NOT_IMPLEMENTED     => 'Not Implemented',
            self::SERVICE_UNAVAILABLE => 'Service Unavailable',
        };
    }
}