<?php

use DiDom\Document;

require 'services/html/autoload.php';
require 'provider.class.php';


App::get('product-info', function () {
    $curl     = @App::$request["curl"];
    Func::emptyCheck([$curl]);
    $document = new Document();
    $document->loadHtmlFile($curl);
    $provider = new Provider($document, $curl);
    $data =  $provider->handle();

    App::db()->insert(
        "products",
        [
            "curl"     => $data["curl"],
            "name"     => $data["name"],
            "price"    => $data["price"],
            "reference"     => $data["reference"],
            "short_description"     => $data["short_description"],
            "image"     => $data["image"],
            "description"     => $data["description"],
            "images"     => implode(',', $data["images"]),
            "category_id"     => 1,
            "provider_id"     => 1,
            "views"     => 0,
            "likes"     => 0
        ]
    );

    App::$response["info"] = $data;
});

App::get('sitemap-convert', function () {
    $url = App::$request['url'];
    $fileContents = file_get_contents($url);
    $simpleXml = simplexml_load_string($fileContents, "SimpleXMLElement", LIBXML_NOCDATA);
    App::$response["result"] = $simpleXml;
});
