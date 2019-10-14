<?php
class Resto
{

    private static $db;

    public static function init($db)
    {
        self::$db = $db;
    }
}
