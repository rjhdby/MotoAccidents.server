<?php

class ApkDB
    extends mysqli
{
    const HOST  = 'apk.host';
    const LOGIN = 'apk.login';
    const PASS  = 'apk.password';
    const DB    = 'apk.db';

    function __construct()
    {
        parent::__construct(Config::get(self::HOST), Config::get(self::LOGIN), Config::get(self::PASS), Config::get(self::DB));
        $this->set_charset("utf8");
    }
}