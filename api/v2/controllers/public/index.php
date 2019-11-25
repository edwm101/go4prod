<?php

$router->mount('/products', function () use ($router) {
    include_once("products.php");
});


$router->mount('/providers', function () use ($router) {
    include_once("providers.php");
});
