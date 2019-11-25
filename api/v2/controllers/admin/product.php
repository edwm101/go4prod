<?php

$router->post('product', function () {
    global $result, $db, $_post;

    $data =  array(
        "curl"     => $_post->curl,
        "name"     => $_post->name,
        "price"    => $_post->price,
        "reference"     => $_post->reference,
        "short_description"     => $_post->short_description,
        "image"     => $_post->image,
        "description"     => $_post->description,
        "images"     => implode(',', $_post->images),
        "category_id"     => 1,
        "provider_id"     => 1,
        "views"     => 0,
        "likes"     => 0
    );

    try {
        $db->insert("products", $data);
        exit();
    } catch (PDOException $e) {
        Func::error($e->getMessage(), 400);
    }
});

$router->get('product/{id}', function ($id) {
    global $result, $db, $_post;

    try {
        $result["info"] = $db->select("*")->from("products")->where("id=?", $id)->execute()->fetchObject();
    } catch (PDOException $e) {
        Func::error($e->getMessage(), 400);
    }
});

$router->put('product/', function () {
    global $result, $db, $_post;

    $id = $_post->id;
    $data =  array(
        "id"     => $id,
        "curl"     => $_post->curl,
        "name"     => $_post->name,
        "price"    => $_post->price,
        "reference"     => $_post->reference,
        "short_description"     => $_post->short_description,
        "image"     => $_post->image,
        "description"     => $_post->description,
        "images"     => implode(',', $_post->images),
        "category_id"     => 1,
        "provider_id"     => 1,
    );

    try {
        $db->update("products",$data,"id=?",$id);
        $result["info"] = $db->select("*")->from("products")->where("id=?", $id)->execute()->fetchObject();
    } catch (PDOException $e) {
        Func::error($e->getMessage(), 400);
    }
});

$router->delete('product/{id}', function ($id) {
    global $result, $db;
    try {
        $db->delete("products","id=?",$id);
        $result["message"] = "Your product has been deleted";
    } catch (PDOException $e) {
        Func::error($e->getMessage(), 400);
    }
});