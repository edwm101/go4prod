<?php

App::post('', function () {
    App::db()->insert(
        "products",
        [
            "curl"     => App::$request["curl"],
            "name"     => App::$request["name"],
            "price"    => App::$request["price"],
            "reference"     => App::$request["reference"],
            "short_description"     => App::$request["short_description"],
            "image"     => App::$request["image"],
            "description"     => App::$request["description"],
            "images"     => implode(',', App::$request["images"]),
            "category_id"     => 1,
            "provider_id"     => 1,
            "views"     => 0,
            "likes"     => 0
        ]
    );
});

App::get('', function () { 
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    App::$response["email"] = TOKEN_EMAIL;
    App::$response["info"] = ShQuery::info("*", "produc", $id);
});

App::put('', function () {
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    App::$response["result"] = App::db()->update(
        "products",
        [
            "id"     => $id,
            "curl"     => App::$request["curl"],
            "name"     => App::$request["name"],
            "price"    => App::$request["price"],
            "reference"     => App::$request["reference"],
            "short_description"     => App::$request["short_description"],
            "image"     => App::$request["image"],
            "description"     => App::$request["description"],
            "images"     => implode(',', App::$request["images"])
        ],
        "id=?",
        $id
    );
});

App::delete('', function () {
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    App::db()->delete("products", "id=?", $id);
});
