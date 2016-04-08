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
    }
}