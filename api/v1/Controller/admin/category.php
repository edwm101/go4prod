<?php

$router->post('category', function () {
    global $result, $db, $_post;

    $data =  array(
        "parent_id"     => @$_post->parent_id,
        "name"     => $_post->name,
        "cat_id"    => $_post->cat_id,
    );

    try {
        $db->insert("categories", $data);
        $result["info"] = ShQuery::lastInsert("*", "categories");
    } catch (PDOException $e) {
        Func::error($e->getMessage(), 400);
    }
});

$router->get('category/{id}', function ($id) {
    global $result, $db, $_post;

    try {
        $result["info"] = $db->select("*")->from("categories")->where("id=?", $id)->execute()->fetchObject();
    } catch (PDOException $e) {
        Func::error($e->getMessage(), 400);
    }
});

$router->put('category/', function () {
    global $result, $db, $_post;

    $id = $_post->id;
    $data =  array(
        "id"     => $id,
        "name"     => $_post->name,
    );
    try {
        $db->update("categories", $data, "id=?", $id);
        $result["info"] = $db->select("*")->from("categories")->where("id=?", $id)->execute()->fetchObject();
    } catch (PDOException $e) {
        Func::error($e->getMessage(), 400);
    }
});

$router->delete('category/{id}', function ($id) {
    global $result, $db;
    try {
        $db->delete("categories", "id=?", $id);
        $result["message"] = "Your category has been deleted";
    } catch (PDOException $e) {
        Func::error($e->getMessage(), 400);
    }
});
