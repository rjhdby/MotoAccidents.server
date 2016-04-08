<?php

class WrongMethod
    extends Core
{

    /**
     * WrongMethod constructor.
     */
    public function __construct()
    {
        $this->error = self::WRONG_METHOD;
    }
}