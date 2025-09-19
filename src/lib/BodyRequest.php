<?php

namespace PanduputragmailCom\PhpNative\lib;

class BodyRequest {
    public static function bodyData() {
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

        if (stripos($contentType, "application/json") !== false) {
            $json = file_get_contents("php://input");
            return json_decode($json, true) ?? [];
        }

        if (!empty($_POST)) {
            return $_POST;
        }

        if (in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'PATCH'])) {
            parse_str(file_get_contents("php://input"), $putData);
            return $putData;
        }

        return [];
    }

    public static function input(string $key, $default = null) {
        $all = self::bodyData();
        return $all[$key] ?? $default;
    }
}
