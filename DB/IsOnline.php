<?php
require 'Calls.php';

class IsOnline
{

    private static $id;

    public static function isOnline()
    {
        if (isset($_SESSION['id'])) {
            self::$id = $_SESSION['id'];
            (new Sql())->SetOnlineStatus('Users', self::$id, 'true');
        } else {
            (new Sql())->SetOnlineStatus('Users', self::$id, 'false');
        }
    }
}

(new IsOnline())->isOnline();