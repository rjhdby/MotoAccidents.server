<?php

class ChangeCommentStatus
    extends Core
{
    private $id;
    private $cid;
    private $status;

    private $user;

    /**
     * ChangeCommentStatus constructor.
     * @param array $data
     *
     * auth required
     *
     * id  - accident id
     * cid - comment id
     * s   - new status
     *       a = active
     *       h = hidden
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
        $this->checkPrerequisites($data, array('id', 's', 'cid'));
        $this->id     = $data['id'];
        $this->cid    = $data['cid'];
        $this->status = Cast::commentStatus($data['s']);
        $this->changeStatus();
        if ($this->error) return;
        $this->changeForumComment();
    }

    private function changeStatus()
    {
        $query = 'UPDATE messages SET status=?, modified=NOW() WHERE id_ent=? AND id=?';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('sii', $this->status, $this->id, $this->id);
        $stmt->execute();
        if ($stmt->error) {
            $this->error = self::DATABASE;
        } else {
            $this->setResult(array('s' => self::OK));
        }
    }

    private function changeForumComment()
    {
    }
}