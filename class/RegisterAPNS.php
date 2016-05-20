<?php

class RegisterAPNS
    extends Core
{
    private $id;
    private $key;

    /**
     * @param $data
     *
     * auth required
     *
     * id - user id
     * k  - APNS key
     * i  - imei (optional)
     */
    public function __construct($data)
    {
        //new Auth($data);
        if ($this->error) return;
        $this->checkPrerequisites($data, array('id', 'k'));
        if ($this->error) return;
        $this->id    = $data['id'];
        $this->key   = $data['k'];
        $this->register();
    }

    private function register()
    {
        $query = 'INSERT INTO devices_ios (id_user,`key`) VALUES (?,?)
                  ON DUPLICATE KEY UPDATE registered=CURRENT_TIMESTAMP';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('is', $this->id, $this->key);
        $stmt->execute();
        if ($stmt->error) {
            $this->error = self::DATABASE;
        } else {
            $this->setResult(array('s' => self::OK));
        }
    }
}