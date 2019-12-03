<?php

class Func
{


    public static function time()
    {
        return strtotime('now Europe/Paris');
    }

    public static function spaceFix($value)
    {
        return trim(preg_replace('/\s+/', ' ', $value));
    }

    public static function emptyCheck($params = [])
    {
        $empty_elements = [];
        foreach ($params as $key => $value) {
            if (!isset($value) || is_null($value) || empty($value))
                self::error("IS_NULL", 400);
        }
    }

    public static function recaptcha($recaptchaToken)
    {
        $recaptchaInfo =  json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . RECAPTCHA_KEY . "&response=" . $recaptchaToken), false);
        if ($recaptchaInfo->success)
            return true;

        return false;
    }


    public static function urlEexists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    public static function requestMap($query)
    {
        $api_keys = [
            "AIzaSyCnbCN9zcMhrD1KzEe__utGsZ4MN5IrKTU",
            "AIzaSyBJiQZyvPNERRKjmN4nnsrA6WFQQTnwYKA",
            "AIzaSyBjekIVdzGQ1EZqnWJyxwNp5lkiQJWcppo",
            "AIzaSyAwkUR61tzMvJYaKOzbovIffCPBVpijnl0",
            "AIzaSyCAYorWuqzvRAPmNRs8C95Smp7hhdATzc8",
            "AIzaSyDZfSnQBIu3V5N9GWbpKGtAUYmDDyxPonU",
            "AIzaSyDqEFJTjKx6L-RpoT-nPiqTi1KJVJimH3I",
            "AIzaSyD6AreMUlOQ90uaRuERD8J4Jv5DnQ85Xys"
        ];

        $data = [];
        foreach ($api_keys as $key => $api_key) {

            $output = file_get_contents(preg_replace('/\s+/', '%20', "https://maps.googleapis.com/maps/api/place/" . $query . "&key=" . $api_key));
            $data =  json_decode($output, false);
            if ($data->status == "OK" || $data->status == "ZERO_RESULTS")  break;
            $data->current = $key + 1;
        }
        return  $data;
    }

    public static function request($url, $method = "GET", $data = [])
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $method = strtoupper($method);
        switch ($method) {
            case "GET":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
        }

        $response = curl_exec($curl);
        print_r($response);
        $data =  json_decode($response, false);
        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // Check the HTTP Status code
        switch ($httpCode) {
            case 200:
                $error_status = "200: Success";
                return ($data);
                break;
            case 404:
                $error_status = "404: API Not found";
                break;
            case 500:
                $error_status = "500: servers replied with an error.";
                break;
            case 502:
                $error_status = "502: servers may be down or being upgraded. Hopefully they'll be OK soon!";
                break;
            case 503:
                $error_status = "503: service unavailable. Hopefully they'll be OK soon!";
                break;
            default:
                $error_status = "Undocumented error: " . $httpCode . " : " . curl_error($curl);
                break;
        }
        curl_close($curl);
        return $error_status;
        die;
    }

    public static function error($status = null, $reponse = 401, $errorMsg = null)
    {
        http_response_code($reponse);

        if ($status == null)
            $status = self::httpResponseMsg($reponse);
        // show error message
        echo json_encode(array(
            "reponse"  => $reponse,
            "status"  => $status,
            "error_message"   => $errorMsg
        ));

        exit;
    }

    public static function checkImage($image)
    {
        if (strpos($image, "http") !== false) {
            return $image;
        } else {
            return BASE_URL . "$image";
        }
    }

    public static function setArray($array, $propertie, $value, $where = 'left')
    {
        foreach ($array as $key => $val) {
            $prop_value = $array[$key][$propertie];
            if (!empty($prop_value)) {
                if ($where == 'left')
                    $array[$key][$propertie] = self::checkImage($value . $prop_value);
                else
                    $array[$key][$propertie] = self::checkImage($prop_value . $value);
            }
        }

        return $array;
    }


    //Remove the specific properties of an object in an array (Ex password or email)
    public static function unset($array, $properties)
    {
        $properties = explode(',', $properties);
        foreach ($array as $key => $val) {
            foreach ($properties as $propertie) {
                unset($array[$key][$propertie]);
            }
        }

        return $array;
    }

    //To make slug (URL string)
    public static function slugify($text, $maxLenght = 5)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return substr($text, 0, $maxLenght);
    }



    public static function randomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function urlMix($name, $nameLength = 10, $randomLength = 10)
    {
        return trim(self::slugify($name, $nameLength) . '-' . self::randomString($randomLength), '-');
    }


    public static function upload($file, $name, $dir = UPLOAD_PATH, $allowed = array('image/*'), $maxSize = 1024)
    {

        if (strpos($file, 'data:') == false) {
            $_data = file_get_contents($file);
            $file = 'data:;base64,' . base64_encode($_data);
        }

        $handle = new upload($file, 'fr_FR');

        if ($handle->uploaded) {
            $handle->allowed = $allowed;
            $handle->file_new_name_body = $name;
            $handle->file_max_size = $maxSize * 1000;
            $handle->file_auto_rename = false;
            $handle->process($dir); //The position of the folder
            if ($handle->processed) {
                App::$response["upload"] = true;
                $ext = $handle->file_dst_name_ext;
                $handle->clean();
                return $dir . $name . '.' . $ext;
            } else {
                App::$response["upload"] = false;
                App::$response["upload_error"] = $handle->error;
            }
        } else {
            App::$response["upload"] = false;
            App::$response["upload_error"] = $handle->error;
        }
    }



    //Ex return X-TOKEN VALUE getHeader("X-Token")
    public static function getHeader($key)
    {
        $headers = apache_request_headers();
        return $headers[$key];
    }

    public static function httpResponseMsg($num)
    {
        $array = array(
            100 => 'CONTINUE',
            101 => 'SWITCHING_PROTOCOLS',
            102 => 'PROCESSING', // WEBDAV; RFC 2518
            200 => 'OK',
            201 => 'CREATED',
            202 => 'ACCEPTED',
            203 => 'NON-AUTHORITATIVE_INFORMATION', // SINCE HTTP/1.1
            204 => 'NO CONTENT',
            205 => 'RESET_CONTENT',
            206 => 'PARTIAL_CONTENT',
            207 => 'MULTI-STATUS', // WEBDAV; RFC 4918
            208 => 'ALREADY_REPORTED', // WEBDAV; RFC 5842
            226 => 'IM USED', // RFC 3229
            300 => 'MULTIPLE_CHOICES',
            301 => 'MOVED_PERMANENTLY',
            302 => 'FOUND',
            303 => 'SEE_OTHER', // SINCE HTTP/1.1
            304 => 'NOT_MODIFIED',
            305 => 'USE_PROXY', // SINCE HTTP/1.1
            306 => 'SWITCH_PROXY',
            307 => 'TEMPORARY_REDIRECT', // SINCE HTTP/1.1
            308 => 'PERMANENT_REDIRECT', // APPROVED AS EXPERIMENTAL RFC
            400 => 'BAD_REQUEST',
            401 => 'UNAUTHORIZED',
            402 => 'PAYMENT_REQUIRED',
            403 => 'FORBIDDEN',
            404 => 'NOT_FOUND',
            405 => 'METHOD_NOT_ALLOWED',
            406 => 'NOT_ACCEPTABLE',
            407 => 'PROXY_AUTHENTICATION_REQUIRED',
            408 => 'REQUEST_TIMEOUT',
            409 => 'CONFLICT',
            410 => 'GONE',
            411 => 'LENGTH_REQUIRED',
            412 => 'PRECONDITION_FAILED',
            413 => 'REQUEST_ENTITY_TOO_LARGE',
            414 => 'REQUEST-URI_TOO_LONG',
            415 => 'UNSUPPORTED_MEDIA_TYPE',
            416 => 'REQUESTED_RANGE_NOT_SATISFIABLE',
            417 => 'EXPECTATION_FAILED',
            418 => 'I\'M_A_TEAPOT', // RFC 2324
            419 => 'AUTHENTICATION_TIMEOUT', // NOT IN RFC 2616
            420 => 'ENHANCE_YOUR_CALM', // TWITTER
            420 => 'METHOD_FAILURE', // SPRING FRAMEWORK
            422 => 'UNPROCESSABLE_ENTITY', // WEBDAV; RFC 4918
            423 => 'LOCKED', // WEBDAV; RFC 4918
            424 => 'FAILED_DEPENDENCY', // WEBDAV; RFC 4918
            424 => 'METHOD_FAILURE', // WEBDAV)
            425 => 'UNORDERED_COLLECTION', // INTERNET DRAFT
            426 => 'UPGRADE_REQUIRED', // RFC 2817
            428 => 'PRECONDITION_REQUIRED', // RFC 6585
            429 => 'TOO_MANY_REQUESTS', // RFC 6585
            431 => 'REQUEST_HEADER_FIELDS_TOO_LARGE', // RFC 6585
            444 => 'NO_RESPONSE', // NGINX
            449 => 'RETRY_WITH', // MICROSOFT
            450 => 'BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS', // MICROSOFT
            451 => 'REDIRECT', // MICROSOFT
            451 => 'UNAVAILABLE_FOR_LEGAL_REASONS', // INTERNET DRAFT
            494 => 'REQUEST_HEADER_TOO_LARGE', // NGINX
            495 => 'CERT_ERROR', // NGINX
            496 => 'NO_CERT', // NGINX
            497 => 'HTTP_TO_HTTPS', // NGINX
            499 => 'CLIENT_CLOSED_REQUEST', // NGINX
            500 => 'INTERNAL_SERVER_ERROR',
            501 => 'NOT_IMPLEMENTED',
            502 => 'BAD_GATEWAY',
            503 => 'SERVICE_UNAVAILABLE',
            504 => 'GATEWAY_TIMEOUT',
            505 => 'HTTP_VERSION_NOT_SUPPORTED',
            506 => 'VARIANT_ALSO_NEGOTIATES', // RFC 2295
            507 => 'INSUFFICIENT_STORAGE', // WEBDAV; RFC 4918
            508 => 'LOOP_DETECTED', // WEBDAV; RFC 5842
            509 => 'BANDWIDTH_LIMIT EXCEEDED', // APACHE BW/LIMITED EXTENSION
            510 => 'NOT_EXTENDED', // RFC 2774
            511 => 'NETWORK_AUTHENTICATION_REQUIRED', // RFC 6585
            598 => 'NETWORKREAD_TIMEOUT_ERROR', // UNKNOWN
            599 => 'NETWORK_CONNECT_TIMEOUT_ERROR', // UNKNOWN
        );
        return $array[$num];
    }
}
