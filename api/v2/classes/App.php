<?php
require_once './classes/Router.php';
include_once "services/sasa/autoload.php";

class App extends Router
{
    public static $db, $request = [], $response = [];

    public static function init()
    {
        self::$db = new database\DB(DB_DSN, DB_USER, DB_PASS);
        self::$request = self::initRequest();
    }


    public static function db()
    {
        return self::$db;
    }


    public static function initRequest()
    {
        if (!$_post   = json_decode(file_get_contents("php://input"))) {
            $_post    = json_decode(json_encode($_POST), false);
        }
        $_get         = json_decode(json_encode($_GET), FALSE);
        return   array_merge((array) $_get, (array) $_post);
    }

    public static function request($param = null)
    {
        if ($param != null) {
            return self::$request[$param];
        } else {
            return self::$request;
        }
    }

    public static function response($items = [])
    {
        foreach ($items as $key => $item) {
            self::$response[$key] = $item;
        }
    }


    public static function upload(
        $file,
        $dir,
        $name,
        $maxSize = 1024,
        $allowed = array('image/*')
    ) {

        $base = "static/";
        if (strpos($file, 'data:') == false) {
            $_data = @file_get_contents($file);
            $file = 'data:;base64,' . base64_encode($_data);
        }

        $handle = new upload($file);

        if ($handle->uploaded) {
            $handle->allowed = $allowed;
            $handle->file_new_name_body = $name;
            $handle->file_max_size = $maxSize * 1000;
            $handle->file_auto_rename = false;
            $handle->process($base . $dir);
            if ($handle->processed) {
                self::$response["upload"] = true;
                $ext = $handle->file_dst_name_ext;
                $handle->clean();
                return $base . $dir . $name . '.' . $ext;
            } else {
                self::$response["upload"] = false;
                self::$response["upload_error"] = $handle->error;
            }
        } else {
            self::$response["upload"] = false;
            self::$response["upload_error"] = $handle->error;
        }
    }
}
