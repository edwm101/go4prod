<?php
// show error reporting
error_reporting(E_ALL);
define("SHOW_PDO_EXCEPTION", true);

//DB define
define("DB_DSN", "mysql:host=localhost;dbname=prod_finder");
define("DB_USER", "root");
define("DB_PASS", "");

define('BASE_URL', "http://localhost/go4prod/api/v2/");

//Mailer
define("MAILER_HOST", "ssl0.ovh.net");
define("MAILER_EMAIL", "contact@elfinder.com");
define("MAILER_PASSWORD", "elfinderwm101");

// set your default time-zone
date_default_timezone_set('Europe/Paris');

//The upload folder path
define('UPLOAD_PATH', "static/");

//JWT CONTENT
define("TOKEN_STATEMENT", "id,first_name,last_name,email,role");

//The secret key in JWT
define('JWT_KEY', "FiNDERtn101");

//The token key that is used in the header
define('ID_TOKEN_KEY', "X-Token");

//GOOGLE
//ReCAPTCHA Keys
define('RECAPTCHA_KEY', "6LcVpq0UAAAAAHX2oYlNg02tfUV7mdALoES3SC0e");
define('MAP_API_KEY', "AIzaSyCnbCN9zcMhrD1KzEe__utGsZ4MN5IrKTU");
