<?php require_once './config/core.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: " . ID_TOKEN_KEY);
header("Access-Control-Allow-Methods:  GET,POST,PUT,DELETE,PATCH");


//REQUIRES
require_once './classes/upload/Upload.php';
require_once './config/database.php'; // get database connection  -- (By SASA)
require_once './classes/Router.php';
require_once './classes/Func.php'; //Shared function (STATIC)
require_once './classes/Auth.php'; //Required to handle JWT (STATIC)
require_once './classes/ShQuery.php'; //Shared query between object (STATIC)


//classes initialisation
$router = new \Bramus\Router\Router();
$db     = new database\DB(DB_DSN, DB_USER, DB_PASS);
Auth::init();
ShQuery::init($db);


//Get posted data body or x-www-form
if (!$_post   = json_decode(file_get_contents("php://input")))
    $_post   = json_decode(json_encode($_POST), FALSE);
$_get    = json_decode(json_encode($_GET), FALSE);
$_data = (object) array_merge((array) $_get, (array) $_post);

//Insert the request inside result.. Ex: $result["items"] = query result
$result = array();

/**** ROUTES *****/
$routes = json_decode(file_get_contents('./routes.json'), true);


$router->before('OPTIONS', "{*}", function () {  // Verifies all the headers sent by the server in response
    exit;
});


// Before Router Middleware 
foreach ($routes["auth"] as $key => $item) {

    if (!array_key_exists("methods", $item)) {
        $_methods = 'GET|POST|PUT|DELETE|PATCH';
    } else {
        $_methods =  $item["methods"];
    }

    $router->before($_methods, $item["paths"], function ()  use ($item) {
        if ($token = @Func::getHeader(ID_TOKEN_KEY)) {
            Auth::setToken($token);
            $result = Auth::tryToDecode();
            if (!Auth::isAuth()) {  //To see if the token is valid
                Func::error("INVALID_TOKEN",401,$result);
            }
            if (!in_array(@Auth::authData()->role, $item["roles"])) {  //To see if the current auth have the permission
                Func::error("INVALID_PERMISSION ",401,"Only " . implode(" and ", $item["roles"]) . " can access this content");
            }
            if (array_key_exists("token_keys", $item)) {
                foreach ($item["token_keys"] as $token_key) {
                    if (property_exists(Auth::authData(), $token_key["key"])) {
                        define($token_key["define"], Auth::authData()->{$token_key["key"]});
                    }
                }
            }
        } else {
            Func::error("API_KEY_NOT_EXISIT",401,"You must use an API key to authenticate each request to our Platform APIs.");  //API TOKEN IS NULL
        }
    });
}

// Custom mount Handler -- (Controllers)
foreach ($routes["mounts"] as $key => $item) {
    $router->mount($item["path"], function () use ($router, $item, $db, $_get, $_post) {
        include_once('./controllers/' . $item['controller'] . '.php');
    });
}
/**** ROUTES *****/

// Custom 404 Handler
$router->set404(function () {
    Func::error("PAGE_NOT_FOUND", 404, "404 Not Found");
});

//Thunderbirds are go!
$router->run(function () {
    global $result;
    http_response_code(200);
    $result["status"] = "OK";
    echo json_encode($result);
    $db = null;
});
