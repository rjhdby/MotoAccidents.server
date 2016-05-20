<?php

class CreateComment
    extends Core
{
    private $id;
    private $text;

    private $user;

    /**
     * CreateComment constructor.
     * @param array $data
     *
     * auth required
     *
     * id - accident id
     * t  - comment text
     */
    public function __construct($data)
    {
        $this->user = new Auth($data);
        if ($this->user->getError()) {
            $this->error = $this->user->getError();

            return;
        }
        if ($this->user->isReadonly()) {
            $this->error = self::RIGHTS;

            return;
        }
        $this->checkPrerequisites($data, array('id', 't'));
        $this->id   = $data['id'];
        $this->text = $data['t'];
        $this->addComment();
        if ($this->error) return;
        $this->updateModified();
        $this->addToForum();
    }

    private function addComment()
    {
        $query = 'INSERT INTO messages (id_ent,id_user,text,modified) VALUES (?,?,?,NOW())';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('iis', $this->id, $this->user->getId(), $this->text);
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

    private function addToForum()
    {
    }
}