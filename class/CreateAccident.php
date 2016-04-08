<?php

class CreateAccident
    extends Core
{
    //private $accident;
    private $user;
    private $id;
    private $forumId;
    private $test;
    private $stat;

    /**
     * CreateAccident constructor.
     * @param array $data
     *
     * auth required
     *
     * a - address
     * lat - latitude
     * lon - longitude
     * t - type
     * c - consequences
     * d - description
     * test - test
     * stat - for statistic
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
        $this->checkPrerequisites($data, array('a', 'lat', 'lon', 't', 'c', 'd'));
        if ($this->error) return;

        $this->test = isset($data['test']) ? 1 : 0;
        $this->stat = isset($data['stat']) ? true : false;
        $this->checkFrequency();
        if ($this->error) return;
        $this->addAccident($data);
        if ($this->error) return;
        $this->updateHistory($data);
        $data['id'] = $this->id;
        if (!$this->stat) {
            new AndroidPush($data);
            //TODO Apple Push
            $forumPost = new ForumPost($data);
            $this->updateForumPost($forumPost->getId());
        }
        $this->setResult(array('s' => self::OK));
    }

    private function checkFrequency()
    {
        $threshold = $this->user->isModerator() ? 60 : 600;
        $apkDB     = new ApkDB();
        $query     = 'SELECT IFNULL(UNIX_TIMESTAMP(MAX(created)), 0) FROM entities WHERE owner=?';
        $stmt      = $apkDB->prepare($query);
        $stmt->bind_param('i', $this->user->getId());
        $stmt->execute();
        $timeout = time() - implode('', $stmt->get_result()->fetch_row());
        if ($timeout < $threshold) {
            $this->error        = self::CREATE_TIMEOUT;
            $this->errorDetails = (int)$threshold - (int)$timeout;
        }
        $apkDB->close();
    }

    private function addAccident($data)
    {
        $apkDB = new ApkDB();
        $query = '
                INSERT INTO entities
				(
					created,
					starttime,
					modified,
					owner,
					type,
					lat,
					lon,
					address,
					description,
					status,
					attr,
					is_test,
					acc_type,
					medicine
				) VALUES (NOW(),NOW(),NOW(),?,"mc_accident",?,?,?,?,"acc_status_act","",?,?,?)
				';
        $stmt  = $apkDB->prepare($query);
        $stmt->bind_param('iddssiss',
            $this->user->getId(),
            $data['lat'],
            $data['lon'],
            $data['a'],
            $data['d'],
            $this->test,
            Cast::oldAccType($data['t']),
            Cast::oldMedicineType($data['c'])
        );
        $stmt->execute();
        if ($stmt->error) {
            $this->error = self::DATABASE;
        } else {
            $this->id = $stmt->insert_id;
        }
        $apkDB->close();
    }

    private function updateHistory($data)
    {
        $apkDB   = new ApkDB();
        $details = json_encode(array('lon' => $data['lon'], 'lat' => $data['lat'], 'address' => $data['a']));
        $query   = '
                INSERT INTO history
				(
					id_ent,
					id_user,
					action,
					params
				) VALUES (?,?,"create_mc_acc",?)
				';
        $stmt    = $apkDB->prepare($query);
        $stmt->bind_param('iis', $this->id, $this->user->getId(), $details);
        $stmt->execute();
        $apkDB->close();
    }

    private function updateForumPost($id)
    {
        $apkDB = new ApkDB();
        $query = 'UPDATE entities SET forum_id=?';
        $stmt  = $apkDB->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $apkDB->close();
    }
}
