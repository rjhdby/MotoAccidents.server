<?php

class Config
{
    private static $params;
    private static $path = 'properties.php';

    public static function setConfigPath($path)
    {
        self::$path = $path;
    }

    public static function get($key)
    {
        if (isset(Config::$params[ $key ])) {
            return Config::$params[ $key ];
        } else {
            Config::readConfig();
        }
        if (isset(Config::$params[ $key ])) {
            return Config::$params[ $key ];
        }

        return "";
    }

    private static function readConfig()
    {
        $content = preg_grep("/.*=.*/", file(Config::$path));
        foreach ($content as $row) {
            list($key, $value) = explode("=", $row, 2);
            $key                    = trim($key);
            $value                  = trim(preg_replace("/#.*/", "", $value));
            Config::$params[ $key ] = $value;
        }
    }
}