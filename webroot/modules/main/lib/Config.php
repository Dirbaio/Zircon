<?php

class Config
{
    private static $config = null;


    private static function env($name, $default=null) {
        $res = getenv($name);
        if($res)
            return $res;
        if($default !== null)
            return $default;
        die('Missing envvar ' . $name);
    }

    public static function load()
    {
        self::$config = array(
            'mysql' => array(
                'host' => self::env("ZIRCON_MYSQL_HOST"),
                'user' => self::env("ZIRCON_MYSQL_USER"),
                'password' => self::env("ZIRCON_MYSQL_PASSWORD"),
                'database' => self::env("ZIRCON_MYSQL_DATABASE"),
                'charset' => 'utf8mb4',
                'prefix' => '',
            ),
            'salt' => self::env("ZIRCON_SALT"),
            'title' => 'Zircon',
        );
    }

    public static function get($what)
    {
        if(self::$config == null)
            throw new Exception('Configuration not loaded');
        return self::$config[$what];
    }
}
