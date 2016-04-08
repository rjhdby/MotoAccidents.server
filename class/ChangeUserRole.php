<?php

class ChangeUserRole
    extends Core
{
    private $uid;
    private $role;

    private $user;

    /**
     * ChangeUserStatus constructor.
     * @param array $data
     *
     * auth required
     *
     * uid - user id
     * r   - new status
     *       r = readonly
     *       s = standart    //Fuck up
     *       m = moderator
     */
    public function __construct($data)
    {
        $this->user = new Auth($data);
        if ($this->user->getError()) {
            $this->error = $this->user->getError();

            return;
        }
        if (!$this->user->isModerator()) {
            $this->error = self::RIGHTS;

            return;
        }
        $this->checkPrerequisites($data, array('r', 'uid'));
        $this->uid  = $data['uid'];
        $this->role = $this->castStatus($data['r']);
        $this->changeStatus();
    }

    private function changeStatus()
    {
        $apkDB = new ApkDB();
        $query = 'UPDATE users SET role=? WHERE id=?';
        $stmt  = $apkDB->prepare($query);
        $stmt->bind_param('si', $this->role, $this->uid);
        $stmt->execute();
        if ($stmt->error) {
            $this->error = self::DATABASE;
        } else {
            $this->setResult(array('s' => self::OK));
        }
        $apkDB->close();
    }

    private function castStatus($status)
    {
        switch ($status) {
            case "r":
                return "readonly";
            case "s":
                return "standart";
            case "m":
                return "moderator";
            default:
                return "standart";
        }
    }
}