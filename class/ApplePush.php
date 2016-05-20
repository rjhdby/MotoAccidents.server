<?php

class ApplePush
{

    private $test;
    private $push;

    /**
     * ApplePush constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->test = TEST;
        $this->push = new Push($data);
        $this->send($this->getIds());
    }

    private function send($ids)
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', Config::get('apns.cert.prod'));
        stream_context_set_option($ctx, 'ssl', 'passphrase', Config::get('apns.passphrase'));

        $fp = stream_socket_client(Config::get('apns.url.prod'),
            $err,
            $errStr,
            60,
            STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
            $ctx);
        stream_set_blocking($fp, 0);
        if (!$fp) exit("Failed to connect: $err $errStr" . PHP_EOL);
        foreach ($ids as $id) {
            $body['aps'] = array(
                'alert' => $this->push->getDataForApple(),
                'content-available' => 1
            );
            $payload = json_encode($body);
            $msg = chr(0);
            $msg .= pack('n', 32);
            $msg .= pack('H*', $id);
            $msg .= pack('n', strlen($payload));
            $msg .= $payload;
            fwrite($fp, $msg, strlen($msg));
        }
        fclose($fp);
    }

    private function getIds()
    {
        $out = array();
        $result = ApkDB::getInstance()->query('SELECT `key` FROM devices_ios');
        while ($row = $result->fetch_array()) {
            $out[] = $row[0];
        }
        return $out;
    }

    /**
     * Fake result for appleXPush
     * Legacy code support
     * @return string
     */
    public function getResult()
    {
        return 'OK';
    }
}