<?php

class ForumPost
{
private $id;
    /**
     * ForumPost constructor.
     * @param $data
     */
    public function __construct($data)
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}