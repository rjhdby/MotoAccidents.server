<?php

class RegisterGCM
    extends Core
{
    private $id;
    private $key;
    private $imei;

    /**
     * @param $data
     *
     * auth required
     *
     * id - user id
     * k  - GCM key
     * i  - imei (optional)
     */
    public function __construct($data)
    {
        new Auth($data);
        if ($this->error) return;
        $this->checkPrerequisites($data, array('id', 'k'));
        if ($this->error) return;
        $this->id    = $data['id'];
        $this->key   = $data['k'];
        $this->imei  = isset($data['i']) ? $data['i'] : '';
        $this->register();
    }

    private function register()
    {

        $query = 'SELECT COUNT(*) FROM devices WHERE id_user=? AND imei=?';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('is', $this->id, $this->imei);
        $stmt->execute();
        if ($stmt->num_rows == 0) {
            $this->newDevice();
        } else {
            $this->updateDevice();
        }
    }

    private function newDevice()
    {
        $query = 'INSERT INTO devices (id_user,imei,gcm) VALUES (?,?,?)';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('iss', $this->id, $this->imei, $this->key);
        $stmt->execute();
        if ($stmt->error) {
            $this->error = self::DATABASE;
        } else {
            $this->setResult(array('s' => self::OK));
        }
    }

    private function updateDevice()
    {
        $query = 'UPDATE devices SET gcm=? WHERE id_user=? AND imei=?';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('sis', $this->key, $this->id, $this->imei);
        $stmt->execute();
        if ($stmt->error) {
            $this->error = self::DATABASE;
        } else {
            $this->setResult(array('s' => self::OK));
        }
    }
}