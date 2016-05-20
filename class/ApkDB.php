<?php

class ApkDB
{
    const HOST  = 'apk.host';
    const LOGIN = 'apk.login';
    const PASS  = 'apk.password';
    const DB    = 'apk.db';
    /** @var mysqli $connect */
    private static $connect = null;

    public static function getInstance()
    {
        if (self::$connect == null) {
            self::$connect = new mysqli(
                Config::get(self::HOST), 
                Config::get(self::LOGIN), 
                Config::get(self::PASS), 
                Config::get(self::DB));
            self::$connect->set_charset("utf8");
        }
        return self::$connect;
    }

    private function __construct()
    {

    }
}