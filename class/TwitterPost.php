<?php
require_once 'lib/twitteroauth.php';

class TwitterPost
{

    /**
     * TwitterPost constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $status = Accident::typeString($data['t']);
        if (Accident::medicineString($data['m']) != '') {
            $status .= ', ' . Accident::medicineString($data['m']);
        }
        $status .= ', ' . $data['a'] . ', ' . $data['d'];
        $status            = mb_substr($status, 0, 140);
        $ConsumerKey       = Config::get('twitter.key');
        $ConsumerSecret    = Config::get('twitter.key.secret');
        $AccessToken       = Config::get('twitter.token');
        $AccessTokenSecret = Config::get('twitter.token.secret');
        $connection        = new TwitterOAuth ($ConsumerKey, $ConsumerSecret, $AccessToken, $AccessTokenSecret);
        $connection->host  = "https://api.twitter.com/1.1/";

        $connection->post('statuses/update', array(
            'status' => $status,
            'long'   => $data['lon'],
            'lat'    => $data['lat']
        ));
    }
}