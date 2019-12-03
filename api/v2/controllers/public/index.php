<?php

App::mount('/products', function () {
    include_once("products.php");
});

App::mount('/providers', function () {
    include_once("providers.php");
});
