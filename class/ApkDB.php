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
        //$this->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
    }
}