<?php

class ChangeAccidentStatus
    extends Core
{
    private $user;

    private $id;
    private $status;

    /**
     * ChangeAccidentStatus constructor.
     * @param $data
     *
     * auth required
     *
     * l - login
     * p - passHash
     * id - accident id
     * s - new status
     */
    public function __construct($data)
    {
        $this->user = new Auth($data);
        if ($this->user->getError()) {
            $this->error = $this->user->getError();

            return;
        }
        $this->checkPrerequisites($data, array('id', 's'));
        if ($this->error) return;
        if ($this->user->isReadonly()) {
            $this->error = self::RIGHTS;

            return;
        }
        if (!$this->user->isModerator() && !$this->isOwner()) {
            $this->error = self::RIGHTS;

            return;
        }
        $this->id     = $data['id'];
        $this->status = Cast::accStatus($data['s']);
        $this->updateStatus();
        $this->updateHistory();
    }

    private function isOwner()
    {
        $apkDB = new ApkDB();
        $query = 'SELECT owner FROM entities WHERE id=?';
        $stmt  = $apkDB->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $result = implode($stmt->get_result()->fetch_row());
        $apkDB->close();

        return $result == $this->user->getId();
    }

    private function updateStatus()
    {
        $apkDB = new ApkDB();
        $query = 'UPDATE entities SET status=? WHERE id=?';
        $stmt  = $apkDB->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        if ($stmt->error) {
            $this->error = self::DATABASE;
        } else {
            $this->setResult(array('s' => self::OK));
        }
        $apkDB->close();
    }

    private function updateHistory()
    {
        $apkDB = new ApkDB();
        $query = '
                INSERT INTO history
				(
					id_ent,
					id_user,
					action,
					params
				) VALUES (?,?,?,"")
				';
        $stmt  = $apkDB->prepare($query);
        $stmt->bind_param('iis', $this->id, $this->user->getId(), $this->status);
        $stmt->execute();
        $apkDB->close();
    }
}