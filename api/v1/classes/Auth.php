<?php
include './services/jwt/libs/php-jwt-master/src/BeforeValidException.php';
include './services/jwt/libs/php-jwt-master/src/ExpiredException.php';
include './services/jwt/libs/php-jwt-master/src/SignatureInvalidException.php';
include './services/jwt/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
class Auth
{
    // variables used for jwt
    private  static $key = "TOBEORNOTTOBE"; //!!!!!!!!! 
    private  static $nbf = 3000;
  
    private  static $iat; // Time when JWT was issued.
    private  static $exp; // Expiration time

    public  static $token = null;


    public static function init($hours = 24,$days = 1)
    {
        self::$iat = time();
        self::$exp = time() + 3600 * $hours * $days;
    }

    public static function isAuth()
    {
        try { 
            $decoded = JWT::decode(self::$token, self::$key, array('HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function tryToDecode()
    {
        try {
            // decode jwt
            $result = JWT::decode(self::$token, self::$key, array('HS256'));
            return true;
        }
        // if decode fails, it means jwt is invalid
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function createToken($data)
    {
        $token = array(
            "iat" => self::$iat,
            "nbf" => self::$nbf,
            "exp" => self::$exp,
            "data" => $data
        );
        return JWT::encode($token, self::$key);
    }

    public static function authData()
    {
        // show user details
        return  JWT::decode(self::$token, self::$key, array('HS256'))->data;
    }

 

    public static function setToken($token)
    {
        self::$token = $token;
    }


    public static function setExp($hours = 24,$days = 30)
    {
        self::$exp = time() + 3600 * $hours * $days;
    }
}
