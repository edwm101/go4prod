<?php

use DiDom\Document;

require 'services/html/autoload.php';
require 'provider.class.php';


$router->get('/product-info', function () {
    global $result, $_get;
    $curl     = $_get->curl;
    $provider = $_get->provider;


    $document = new Document();
    $document->loadHtmlFile($curl);

    $provider = new Provider($document,$provider);


    $result["info"] = $provider->handle();

});