<?php

$router->mount('/opening_time', function () use ($router) {
   include_once "entity/OpeningTime.php";
   include_once("opening_time.php");
});

$router->mount('/delivery_zone', function () use ($router) {
   include_once "entity/DeliveryZone.php";
   include_once("delivery_zone.php");
});

$router->mount('/product', function () use ($router) {
   include_once("product.php");
});

$router->mount('/category', function () use ($router) {
   include_once("category.php");
});

$router->mount('/property', function () use ($router) {
   include_once("property.php");
});

$router->mount('/restaurant', function () use ($router) {
   include_once("restaurant.php");
});
