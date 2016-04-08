<?php

class Message
{
    private $id;
    private $owner;
    private $ownerId;
    private $timestamp;
    private $description;

    /**
     * Message constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->id          = $data['id'];
        $this->ownerId     = $data['id_user'];
        $this->owner       = $data['login'];
        $this->timestamp   = $data['uxtime'];
        $this->description = $data['text'];
    }

    /*
     * id - message id
     * o - owner
     * oid - owner Id
     * t - timestamp
     * d - text
     */

    public function get()
    {
        return array(
            'id'  => $this->id,
            'o'   => $this->owner,
            'oid' => $this->ownerId,
            't'   => $this->timestamp,
            'd'   => $this->description
        );
    }
}