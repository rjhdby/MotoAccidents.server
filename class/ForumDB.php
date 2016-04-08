<?php

class ForumDB
    extends mysqli
{
    const HOST  = 'forum.host';
    const LOGIN = 'forum.login';
    const PASS  = 'forum.password';
    const DB    = 'forum.db';

    function __construct()
    {
        parent::__construct(Config::get(self::HOST), Config::get(self::LOGIN), Config::get(self::PASS), Config::get(self::DB));
        $this->set_charset("utf8");
        //$this->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
    }
}