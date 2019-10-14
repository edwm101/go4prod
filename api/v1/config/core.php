<?php
// show error reporting
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set('Europe/Paris');

//The secret key in JWT
define('BASE_URL',"http://localhost/go4prod/api/v1/");

//The secret key in JWT
define('JWT_KEY',"TOBEORNOTTOBE");

//The token key that is used in the header
define('ID_TOKEN_KEY',"X-Token");

//The upload folder path
define('UPLOAD_PATH',"static/");

//GOOGLE
//ReCAPTCHA Keys
define('RECAPTCHA_KEY',"6LcVpq0UAAAAAHX2oYlNg02tfUV7mdALoES3SC0e");
define('MAP_API_KEY',"AIzaSyCnbCN9zcMhrD1KzEe__utGsZ4MN5IrKTU");