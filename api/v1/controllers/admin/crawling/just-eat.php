<?php


$router->get('/', function () {
    global $result, $_get;
    $curl = $_get->curl;

    if (strpos($curl, "test") != false) {
        Func::error("", 400, "INVALID_RESTO");
    }

    $document = new Document();

    $document->loadHtmlFile('http://partner.bensassi.net/crawling?curl=' . $curl);

    $restaurant = [];
    if (count($document->find('a[itemprop=url]')) === 0) {
        Func::error("", 400, "SERVER_PROBLEM");
    }

    $restaurant["name"] = $document->find('a[itemprop=url]')[0]->text();
    $cuisines = [];
    foreach ($document->find('div[class=cuisines] > ul > li') as $cuisine) {
        $cuisines[] = $cuisine->text();
    }
    $restaurant["cuisines"] = $cuisines;

    $restaurant["logo"] =  $document->find('a[class=logo] img::attr(src)')[0];
    $restaurant["banner"] =  $document->find('picture img::attr(src)')[0];
    $restaurant["address"] =  $document->find('span[itemprop=streetAddress]')[0]->text();
    $restaurant["zip_code"] =  $document->find('span[itemprop=postalCode]')[0]->text();
    $restaurant["city"] =  $document->find('span[itemprop=addressRegion]')[0]->text();
    $restaurant["site_url"] =  $curl;
    $restaurant["url_id"] = Func::urlMix($restaurant["name"].' '.$restaurant["address"].' '.$restaurant["city"], 100,2);
     
    
    $placeId = null;
    $placeData = null;
    $mapData = Func::requestMap("findplacefromtext/json?input=restaurant " . $restaurant["name"] . " " . $restaurant["address"] . "&inputtype=textquery");
    if ($mapData->status == 'OK') {
        $placeId =   $mapData->candidates[0]->place_id;
    } else {
        $mapData = Func::requestMap("findplacefromtext/json?input= " . $restaurant["name"] .  "&inputtype=textquery");

        if ($mapData->status == 'OK') {
            $placeId =   $mapData->candidates[0]->place_id;
        }
    }
    if ($mapData->status == 'OK') {
        $placeData = Func::requestMap("details/json?placeid=" . $placeId . "&language=fr&fields=name,user_ratings_total,price_level,place_id,price_level,website,rating,reviews,opening_hours,formatted_phone_number")->result;
    }

    $result["map"] = $placeData;

    $categories = [];
    $menu = $document->find('.products-wrapper > .accordion-item');
    foreach ($menu as $key => $item) {
        $category = [];
        if ($key > 0) {
            $category["name"] =   explode('(', $item->first('.accordion-item-heading')->text(), 2)[0];
            $category["name_id"] =  Func::slugify($category["name"], 50);

            $category["items"] = [];
            foreach ($item->first('ul')->xpath('li') as  $product) {
                $new_product = [];
                if ($product->find("div[class=price]")[0]->hasAttribute("class")) {
                    if ($product->has(".image")) {
                        $new_product["image"] =   $product->find("div[class=image] img::attr(src)")[0];
                    }
                    $new_product["name"] =   $product->find("h4[class=name]")[0]->text();
                    $new_product["price"] =  preg_replace('/[^0-9\.]/', ".", preg_replace('/[^0-9\.,]/', "", $product->find("div[class=price]")[0]->text()));
                    $new_product["description"] =   $product->find("div[class=description]")[0]->text();
                    $category["items"][] = $new_product;
                }
            }
            $categories[] =  $category;
        }
    }

    $document->loadHtmlFile('http://partner.bensassi.net/crawling?curl=' . $curl . '/infos');

    $zones = [];
    foreach ($document->find('ul[class=zones] > li') as $zone) {
        $zones[] = $zone->text();
    }
    $result["zones"] =  $zones;

    $result["restaurant"] = $restaurant;
    $result["menu"] = $categories;
});