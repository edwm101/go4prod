<?php


$router->get('/info', function () {
    global $result;

    $result["category"] = Menu::getCategory(0);
    $result["option"] = Menu::getCategory(1);
    $result["tag"] = Menu::getProperty("tag");
    $result["allergen"] = Menu::getProperty("allergen");
});

$router->get('/all', function () {
    global $result, $_get;

    $is_option = $_get->is_option ?? 0;
    $result["items"] = Menu::getAllProductByCategory($is_option);
});

$router->put('/position', function () {
    global $db, $_post;
    $items = @$_post->items;

    if (empty($items))
        Func::error("", 400,"EMPTY_DATA");

    foreach ($items as $index => $category) {
        $db->update("category", ["position" => $index], "id=? and restaurant_id=?", [$category->id, RESTAURANT_ID]);
        foreach ($category->products as $index2 => $product) {
            $db->update("product", ["position" => $index2], "id=? AND category_id=? and restaurant_id=?", [$product, $category->id,RESTAURANT_ID]);
        }
    }
});
