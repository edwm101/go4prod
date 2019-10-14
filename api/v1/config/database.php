<?php

include_once "./services/sasa/lib/DB.php";
include_once "./services/sasa/lib/Statement.php";
include_once "./services/sasa/lib/Query.php";

if (!defined("DB_DSN")) {
    define("DB_DSN", "mysql:host=localhost;dbname=restoking");
    define("DB_USER", "root");
    define("DB_PASS", "");
}




?>