<?php

namespace App\Model;

class Cookie
{
    public static $instance;

    public function __construct()
    {
    }

    public function setCookie($key, $value)
    {
        setcookie($key, $value, time() + 3600 * 24, '/', '', true, true);
    }

    public function read($key)
    {
        isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function delete($key)
    {
        unset($_SESSION[$key]);
        session_destroy();
    }
}
