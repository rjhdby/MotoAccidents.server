<?php

/**
 * MAX(a.id) AS id,
 * a.id_user,
 * b.login,
 * MAX(UNIX_TIMESTAMP(a.timest)) AS uxtime,
 * a.action
 */
class History
{
    private $id;
    private $owner;
    private $ownerId;
    private $timestamp;
    private $action;

    /**
     * Message constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->id        = $data['id'];
        $this->ownerId   = $data['id_user'];
        $this->owner     = $data['login'];
        $this->timestamp = $data['uxtime'];
        $this->action    = $this->actionWith($data['action']);
    }

    /*
     * id - message id
     * o - owner
     * oid - owner Id
     * t - timestamp
     * a - action
     */

    public function get()
    {
        return array(
            'id'  => $this->id,
            'o'   => $this->owner,
            'oid' => $this->ownerId,
            't'   => $this->timestamp,
            'a'   => $this->action
        );
    }

    private function actionWith($action)
    {
        switch ($action) {
            case "acc_status_act":
                return "act";
            case "acc_status_end":
                return "end";
            case "acc_status_hide":
                return "hide";
            case "create_mc_acc":
                return "create";
            case "finish_mc_acc":
                return "finish";
            default:
                return $action;
        }
    }
}