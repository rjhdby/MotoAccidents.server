<?php

class GetList
    extends Core
{

    /** @var Auth $user */
    private $user;
    private $test;
    private $age;

    private $accidents;

    /**
     * GetList constructor.
     * @param array $data
     *
     * l - login
     * p - passHash
     * h - hours ago
     */
    public function __construct($data)
    {
        $this->test = TEST;
        $this->user = new Auth($data);
        $this->age = isset($data['h']) ? $data['h'] : 24;
        if ($this->user->isModerator()) $this->test = 1;
        $this->requestList();
        $this->setResult($this->accidents);

        //var_dump($this->result);
    }

    private function requestList()
    {
        $apkDB = new ApkDB();
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
					a.medicine AS med,
					a.is_test AS test
				FROM
					entities a,
					acc_statuses b,
					users c
				WHERE
					1=1
					AND b.status=a.status
					AND a.owner=c.id
					AND a.status != "acc_status_dbl"
					AND NOW() < (DATE_ADD(a.starttime, INTERVAL ? HOUR))
					AND is_test IN (0,?)
					';

        $stmt = $apkDB->prepare($query);
        $stmt->bind_param('ii', $this->age, $this->test);
        $stmt->execute();
        $result = $stmt->get_result();
        $apkDB->close();
        while ($row = $result->fetch_assoc()) {
            $accident = new Accident($row);
            $this->accidents[] = $accident->get();
        }
    }
}