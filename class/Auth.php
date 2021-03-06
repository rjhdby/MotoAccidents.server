<?php

class Auth
    extends Core
{
    /*
     * l - login
     * p - password
     * i - phone imei
     */

    /* input */
    private $login;
    private $passHash;
    /* output */
    private $id   = 0;
    private $role = Cast::STANDARD_ROLE;
    /* internal */
    private $imei;

    /**
     * Auth constructor.
     * @param $data - $_GET array
     */
    public function __construct($data)
    {
        $this->checkPrerequisites($data, array('l', 'p'));
        if ($this->error) return;
        $this->login    = $data['l'];
        $this->passHash = $data['p'];
        $this->imei     = isset($data['i']) ? $data['i'] : '';
        $this->auth();
    }

    private function auth()
    {
        $this->authOnForum();
        if ($this->error) return;

        $result = $this->getUser();

        if ($result->num_rows == 0) {
            $this->createUser();
        } else {
            $result     = $result->fetch_assoc();
            $this->id   = $result['id'];
            $this->role = Cast::userRole($result['role']);
            $this->updateUser();
        }
        $this->setResult(array('id' => $this->id, 'r' => $this->role));
    }

    private function getUser()
    {
        $query = 'SELECT id, role FROM users WHERE login=?';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('s', $this->login);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    private function authOnForum()
    {
        $forumDB = new ForumDB();
        $query   = '
				SELECT
					members_pass_hash,
					members_pass_salt
				FROM members
				WHERE name=?';
        $stmt    = $forumDB->prepare($query);
        $stmt->bind_param('s', $this->login);
        $stmt->execute();
        $result = $stmt->get_result();
        $forumDB->close();
        if ($result->num_rows == 0) {
            $this->error = self::USER_UNKNOWN;

            return;
        }
        $result = $result->fetch_assoc();
        if (md5(md5($result['members_pass_salt']) . $this->passHash) !== $result['members_pass_hash']) {
            $this->error = self::CREDENTIALS;

            return;
        }
    }

    private function updateUser()
    {
        $query = 'UPDATE users SET lastlogin=NOW() WHERE login=?';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('s', $this->login);
        $stmt->execute();
    }

    private function createUser()
    {
        $query = 'INSERT INTO users (login,register,imei) VALUES (?,NOW(),?)';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('ss', $this->login, $this->imei);
        $stmt->execute();
        $this->id   = $stmt->insert_id;
        $this->role = Cast::STANDARD_ROLE;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getImei()
    {
        return $this->imei;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    public function isModerator()
    {
        switch ($this->role) {
            case Cast::MODERATOR_ROLE:
            case Cast::DEVELOPER_ROLE:
                return true;
            default:
                return false;
        }
    }

    public function isReadonly()
    {
        return $this->role == Cast::READ_ONLY_ROLE;
    }
}