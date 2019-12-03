<?php require_once 'core.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: " . ID_TOKEN_KEY);
header("Access-Control-Allow-Methods:  GET,POST,PUT,DELETE,PATCH");


//REQUIRES
require_once './classes/upload/Upload.php';
require_once './classes/Func.php'; //Shared function (STATIC)
require_once './classes/Auth.php'; //Required to handle JWT (STATIC)
require_once './classes/ShQuery.php'; //Shared query between object (STATIC)
require_once './classes/App.php';


//classes initialisation
App::init();
Auth::init();
ShQuery::init(App::db());


/**** ROUTES *****/

App::before('OPTIONS', "{*}", function () {
    exit;
});

$routes = json_decode(file_get_contents('./routes.json'), true);
// Before Router Middleware 
foreach ($routes["auth"] as $key => $item) {

    (!array_key_exists("methods", $item)) ?
        $_methods = 'GET|POST|PUT|DELETE|PATCH' : $_methods =  preg_replace('/\s+/', '', strtoupper($item["methods"]));


    App::before(
        $_methods,
        preg_replace('/\s+/', '', $item["paths"]),
        function ()  use ($item) {
            if ($token = @Func::getHeader(ID_TOKEN_KEY)) {
                Auth::setToken($token);
                $result = Auth::tryToDecode();
                if (!Auth::isAuth()) {  //To see if the token is valid
                    Func::error("INVALID_TOKEN", 401, $result);
                }
                if (!in_array(@Auth::authData()->role, $item["roles"])) {  //To see if the current auth have the permission
                    Func::error("INVALID_PERMISSION ", 401, "Only " . implode(" and ", $item["roles"]) . " can access this content");
                }
                foreach (Auth::authData() as $key => $token_key) {
                    define(strtoupper("TOKEN_" . $key), $token_key);
                }
            } else {
                Func::error("API_KEY_NOT_EXISIT", 401, "You must use an API key to authenticate each request to our Platform APIs.");  //API TOKEN IS NULL
            }
        }
    );
}

// Custom mount Handler -- (Controllers)
foreach ($routes["mounts"] as $key => $item) {
    App::mount($item["path"], function () use ($item) {
        include_once('./controllers/' . $item["controller"] . '.php');
    });
}
/**** ROUTES *****/

// Custom 404 Handler
App::set404(function () {
    Func::error("PAGE_NOT_FOUND", 404, "404 Not Found");
});

//Thunderbirds are go!
App::run(function () {
    http_response_code(200);
    App::response(["status" => "OK"]);
    echo json_encode(App::$response);
});
