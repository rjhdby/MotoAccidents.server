<?php

class Push
{
    private $data;

    /**
     * Push constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /*
     * t - type
     * a - address
     * m - medicine
     * c - created
     */

    public function getDataForAndroid()
    {
        return array(
            'id'  => $this->data['id'],
            'lat' => $this->data['lat'],
            'lon' => $this->data['lon'],
            't'   => $this->data['t'],
            'a'   => $this->data['a'],
            'm'   => $this->data['m'],
            'c'   => time()
        );
    }

    public function getDataForApple(){
        return array(
            'id'=>$this->data['id']
        );
    }
}