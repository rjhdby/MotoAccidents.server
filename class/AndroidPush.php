<?php

class AndroidPush
{

    private $test;
    private $push;

    /**
     * AndroidPush constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->test = isset($data['test']) ? 1 : 0;
        $this->push = new Push($data);
        $this->sendBroadcast();
    }

    private function sendBroadcast()
    {
        $result = "";
        $ids    = $this->getIds();
        for ($i = 0; $i < count($ids); $i += 20) {
            $result .= $this->send(array_slice($ids, $i, 20));
        }
    }

    private function send($ids)
    {
        $header = array(
            'Authorization: key=' . Config::get('gcm.key'),
            'Content-Type: application/json'
        );
        $post   = array(
            'registration_ids' => $ids,
            'data'             => $this->push->getDataForAndroid()
        );
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::get('gcm.url'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $response = 'GCM error: ' . curl_error($ch);
        } else {
            $this->updateIds($ids, json_decode($response, true)['results']);
        }
        curl_close($ch);

        return $response;
    }

    private function updateIds($ids, $response)
    {
        for ($i = 0; $i < count($ids); $i++) {
            if (isset($response[ $i ]['error'])) {
                if ($response[ $i ]['error'] == 'NotRegistered') {
                    $query = 'DELETE FROM devices WHERE gcm=?';
                    $stmt  = ApkDB::getInstance()->prepare($query);
                    $stmt->bind_param('s', $ids[ $i ]);
                    $stmt->execute();
                    //echo $ids[ $i ] . "   " . $response[ $i ]['error'] . "<br>";
                }
            }
        }
    }

    private function getIds()
    {
        if ($this->test) {
            $result = $this->getDevelopersIds();
        } else {
            $result = $this->getAllIds();
        }
        $out = array();
        while ($id = $result->fetch_row()) {
            $out[] = implode($id);
        }

        return $out;
    }

    private function getAllIds()
    {
        $query  = 'SELECT DISTINCT gcm FROM devices WHERE NOT gcm IS NULL';
        $result = ApkDB::getInstance()->query($query);

        return $result;
    }

    private function getDevelopersIds()
    {
        $query  = '
                SELECT DISTINCT a.gcm
                FROM devices a, users b
                WHERE 1=1
                  AND a.id_user=b.id
                  AND b.role IN ("developer")
                  AND NOT a.gcm IS NULL
                  ';
        $result = ApkDB::getInstance()->query($query);

        return $result;
    }
}