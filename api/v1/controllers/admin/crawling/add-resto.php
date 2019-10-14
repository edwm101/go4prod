<?php
$router->post('/add-resto', function () {
    global $_post, $result, $db;

    $restaurant = $_post->restaurant;
    $map = $_post->map;
    $menu = $_post->menu;

    $restaurant->cuisines = implode(",", $restaurant->cuisines);
    $restaurant->phone = $map->formatted_phone_number;
    $restaurant->price_level = $map->price_level;

    $db->insert("restaurant", $restaurant);
    $restaurant_id = $db->lastInsertId();
    
    foreach ($menu as $key => $category) {

        $category_data = array(
            "restaurant_id" => RESTAURANT_ID,
            "name" => $category->name,
        );
        $db->insert("category", $category_data);
        $category_id = $db->lastInsertId();

        foreach ($category->product as $keyP => $product) {
            $product_data = [
                "restaurant_id" => $restaurant_id,
                "category_id" => $category_id,
                "name" => $product->name,
                "description" =>  $product->description,
                "price" =>  $product->price,
                "tva_id" => 10000,
                "main_image_url" => $product->image,
                "position" => 1,
            ];
            $db->insert("product", $product_data);
        }
    }
});