<?php

class GetSimpleList
    extends Core
{
    private $begin;
    private $end;

    private $accidents;

    /**
     * GetList constructor.
     * @param array $data
     *
     * b - begin timestamp
     * e - end timestamp
     *
     */
    public function __construct($data)
    {
        $this->checkPrerequisites($data, array('b', 'e'));
        if ($this->error) return;
        $this->begin = $data['b'];
        $this->end = $data['e'];
        $this->requestList();
        $this->setResult($this->accidents);
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
					AND UNIX_TIMESTAMP(a.starttime) BETWEEN ? AND ?
					AND is_test = 0
					';

        $stmt = $apkDB->prepare($query);
        $stmt->bind_param('ii', $this->begin, $this->end);
        $stmt->execute();
        $result = $stmt->get_result();
        $apkDB->close();
        while ($row = $result->fetch_assoc()) {
            $accident = new SimpleAccident($row);
            $this->accidents[] = $accident->get();
        }
    }
}