<?php

class Volunteer
{
    private $owner;
    private $ownerId;
    private $timestamp;
    private $status;

    /**
     * Message constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->ownerId   = $data['id_user'];
        $this->owner     = $data['login'];
        $this->timestamp = $data['uxtime'];
        $this->status    = $data['status'];
    }

    /*
     * o - owner
     * oid - owner Id
     * t - timestamp
     * s - status
     */

    public function get()
    {
        return array(
            'o'   => $this->owner,
            'oid' => $this->ownerId,
            't'   => $this->timestamp,
            's'   => $this->status
        );
    }
}
