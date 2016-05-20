<?php

/**
 * Created by PhpStorm.
 * User: U_60A9
 * Date: 29.03.2016
 * Time: 17:30
 */
class GetAccident
    extends Core
{
    private $id;

    /**
     * GetAccident constructor.
     * @param array $data
     *
     * id - accident id
     */
    public function __construct($data)
    {
        $this->checkPrerequisites($data, array('id'));
        if ($this->error) return;
        $this->id = $data['id'];
        $this->getAccident();
    }

    private function getAccident()
    {
        $query = 'SELECT
					a.id,
					UNIX_TIMESTAMP(a.created) AS uxtime,
					a.address,
					a.description AS descr,
					a.status,
					c.login AS owner,
					a.owner AS owner_id,
					a.lat,
					a.lon,
					a.acc_type AS type,
					a.medicine AS med
				FROM
					entities a,
					acc_statuses b,
					users c
				WHERE
					a.id=?
				LIMIT 1
					';
        $stmt  = ApkDB::getInstance()->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $row      = $stmt->get_result()->fetch_assoc();
        $accident = new Accident($row);
        $this->setResult(array($accident->get()));
    }
}