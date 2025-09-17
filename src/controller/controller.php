<?php
namespace PanduputragmailCom\PhpNative\Controller;

use PanduputragmailCom\PhpNative\Database\Database;

class Controller
{
    public function ConnectDB()
    {
        $db = new Database();
        return $db->connection();
    }
}