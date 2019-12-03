<?php

App::mount('/product', function () {
    include_once("product.php");
});


App::mount('/crawling', function () {
    include_once("crawling/index.php");
});
