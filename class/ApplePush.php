<?php

class ApplePush
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
        $ids = $this->getIds();
        /*
        for ($i = 0; $i < count($ids); $i += 20) {
            $result .= $this->send(array_slice($ids, $i, 20));
        }
        */
        foreach ($ids as $id) {
            $result = $this->send($id);
        }
    }

    private function send($id)
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', Config::get('apns.cert.test'));
        stream_context_set_option($ctx, 'ssl', 'passphrase', Config::get('apns.passphrase'));

        $fp = stream_socket_client(Config::get('apns.url.test'),
            $err,
            $errStr,
            60,
            STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
            $ctx);
        stream_set_blocking($fp, 0);
        if (!$fp) exit("Failed to connect: $err $errStr" . PHP_EOL);
//        $appleExpiry = time() + (2 * 60 * 60); // держать живым 2 часа

        $body['aps'] = array(
            'alert' => $this->push->getDataForApple(),
            'sound' => 'default',
            'content-available' => 1
        );

        $payload = json_encode($body);

        $msg = chr(0);
        $msg .= pack('n', 32);
        $msg .= pack('H*', $id);
        $msg .= pack('n', strlen($payload));
        $msg .= $payload;

        var_dump($msg);

        $result = fwrite($fp, $msg, strlen($msg));

        $apple_error_response = fread($fp, 6);

        fclose($fp);

        return $result;
    }

    private function updateIds($ids, $response)
    {
        $apkDB = new ApkDB();
        for ($i = 0; $i < count($ids); $i++) {
            if (isset($response[$i]['error'])) {
                if ($response[$i]['error'] == 'NotRegistered') {
                    $query = 'DELETE FROM devices WHERE gcm=?';
                    $stmt = $apkDB->prepare($query);
                    $stmt->bind_param('s', $ids[$i]);
                    $stmt->execute();
                    //echo $ids[ $i ] . "   " . $response[ $i ]['error'] . "<br>";
                }
            }
        }
        $apkDB->close();
    }

    private function getIds()
    {
        $out = array();
        $apkDB = new ApkDB();
        $result = $apkDB->query('SELECT `key` FROM devices_ios');
        while ($row = $result->fetch_array()) {
            $out[] = $row[0];
        }
        $apkDB->close();
        return $out;
        //return array('74b36c00589d55376434476381bd6ddda39aba8d44b3d836f3ef6e76a13cd541');
    }

    private function getAllIds()
    {
        $apkDB = new ApkDB();
        $query = 'SELECT DISTINCT gcm FROM devices WHERE NOT gcm IS NULL';
        $result = $apkDB->query($query);
        $apkDB->close();

        return $result;
    }

    private function getDevelopersIds()
    {
        $apkDB = new ApkDB();
        $query = '
                SELECT DISTINCT a.gcm
                FROM devices a, users b
                WHERE 1=1
                  AND a.id_user=b.id
                  AND b.role IN ("developer")
                  AND NOT a.gcm IS NULL
                  ';
        $result = $apkDB->query($query);
        $apkDB->close();

        return $result;
    }
}