<?php

class ChangeVolunteerStatus
    extends Core
{

    private $id;
    private $uid;
    private $status;

    private $user;

    /**
     * ChangeVolunteerStatus constructor.
     * @param array $data
     *
     * auth required
     *
     * id  - accident id
     * uid - volunteer id
     * s   - new status
     *       w = onway
     *       i = inplace
     *       l = leave
     */
    public function __construct($data)
    {
        $this->user = new Auth($data);
        if ($this->user->getError()) {
            $this->error = $this->user->getError();

            return;
        }
        $this->checkPrerequisites($data, array('id', 's', 'uid'));
        $this->id     = $data['id'];
        $this->uid    = $data['uid'];
        $this->status = Cast::volunteerStatus($data['s']);

        if ($this->user->getId() != $this->uid) {
            $this->error = self::RIGHTS;

            return;
        }
        $this->changeStatus();
        $this->updateModified();
    }

    private function changeStatus()
    {
        $query = 'SELECT status FROM onway WHERE id=? AND id_user=?';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('ii', $this->id, $this->uid);
        $stmt->execute();
        if ($stmt->num_rows == 0) {
            $this->newVolunteer();
        } else if (implode($stmt->get_result()->fetch_row()) != $this->status) {
            $this->updateStatus();
        }
    }

    private function newVolunteer()
    {
        $query = 'INSERT INTO onway (id,id_user,status) VALUES (?,?,?)';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('iis', $this->id, $this->uid, $this->status);
        $stmt->execute();
        if ($stmt->error) {
            $this->error = self::DATABASE;
        } else {
            $this->setResult(array('s' => self::OK));
        }
    }

    private function updateStatus()
    {
        $query = 'UPDATE onway SET status=?, timest=NOW() WHERE id=? AND id_user=?';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('sii', $this->status, $this->id, $this->uid);
        $stmt->execute();
        if ($stmt->error) {
            $this->error = self::DATABASE;
        } else {
            $this->setResult(array('s' => self::OK));
        }
    }

    private function updateModified()
    {
        $query = 'UPDATE entities SET modified=NOW() WHERE id=?';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
    }
}