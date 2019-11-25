<?php

$router->get('crawling/sitemap-convert', function () {
    global $_get, $result;
    $url = $_get->url;
    $fileContents = file_get_contents($url);
    $simpleXml = simplexml_load_string($fileContents, "SimpleXMLElement", LIBXML_NOCDATA);
    $result["result"] = $simpleXml;
});