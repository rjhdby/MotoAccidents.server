<?php

class SimpleAccident
{
    private $id;
    private $timestamp;
    private $address;
    private $description;
    private $status;
    private $owner;
    private $ownerId;
    private $lat;
    private $lon;
    private $type;
    private $medicine;
    private $test;

    /**
     * Accident constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->id          = $data['id'];
        $this->timestamp   = $data['uxtime'];
        $this->address     = $data['address'];
        $this->description = $data['descr'];
        $this->status      = self::statusWith($data['status']);
        $this->owner       = $data['owner'];
        $this->ownerId     = $data['owner_id'];
        $this->lat         = $data['lat'];
        $this->lon         = $data['lon'];
        $this->type        = Cast::accType($data['type']);
        $this->medicine    = Cast::medicineType($data['med']);
        $this->test        = $data['test'] == 1 ? true : false;
    }

    /*
     * id - accident id
     * time - unix timestamp
     * a - addrtess
     * d - description
     * s - status
     * o - owner
     * oid - owner Id
     * lat - latitude
     * lon - longitude
     * t - type
     * med - medicine
     * m - messages array
     * v - volunteers array
     * h - history array
     */
    public function get()
    {
        if ($this->test) {
            $this->description = 'TEST!!! ' . $this->description;
            $this->address     = 'TEST!!! ' . $this->address;
        }
        $out = array(
            'id'   => $this->id,
            'time' => $this->timestamp,
            'a'    => $this->address,
            'd'    => $this->description,
            's'    => $this->status,
            'o'    => $this->owner,
            'oid'  => $this->ownerId,
            'lat'  => $this->lat,
            'lon'  => $this->lon,
            't'    => $this->type,
            'med'  => $this->medicine
        );

        return $out;
    }

    /*
     * a - active
     * e - ended
     * d - double
     * h - hidden
     * c - conflict
     */
    private static function statusWith($status)
    {
        switch ($status) {
            case "acc_status_act":
                return "a";
            case "acc_status_end":
                return "e";
            case "acc_status_dbl":
                return "d";
            case "acc_status_hide":
                return "h";
            case "acc_status_war":
                return "c";
            default:
                return "a";
        }
    }

    public static function medicineString($medicine)
    {
        switch ($medicine) {
            case "d":
                return "минус";
            case "h":
                return "тяжелый";
            case "l":
            case "wo":
            case "na":
            default:
                return "";
        }
    }

    public static function typeString($type)
    {
        switch ($type) {
            case "b":
                return "Поломка";
            case "1":
                return "Один";
            case "a":
                return "Мот/авто";
            case "m":
                return "Мот/мот";
            case "p":
                return "Мот/пешеход";
            case "o":
                return "Прочее";
            case "s":
                return "Угон";
            default:
                return "Прочее";
        }
    }
}
