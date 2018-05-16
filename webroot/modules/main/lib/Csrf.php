<?php

session_start();

class Csrf
{
    public static function check($token)
    {
        $goodtoken = self::get();
        if($token !== $goodtoken)
            fail("Bad CSRF token!");
    }

    public static function get()
    {
        if(!$_SESSION["token"])
            $_SESSION["token"] = Util::randomString();
        return $_SESSION["token"];
    }
}
