<?php

$router->get('/property', function () {
    global  $result, $_get;

    $meta_key = @$_get->meta_key;

    if (empty($meta_key))
        Func::error("Your property name is null", 400);

    $result["items"] = Menu::getProperty($meta_key);
});

$router->post('/property', function () {
    global $result, $db, $_post, $_get;

    $meta_key    = @$_get->meta_key;
    $meta_value  = @$_post->meta_value;

    if (empty($meta_key) || empty($meta_value))
        Func::error("Your key or value is null", 400);

    try {
        $count = $db->count("property", "restaurant_id=? and meta_key=? and meta_value=?", array(RESTAURANT_ID, $meta_key, $meta_value));
        if ($count > 0)
            Func::error("", 400, "ALREADY_EXISTS");

        $data = array(
            "restaurant_id" => RESTAURANT_ID,
            "meta_key" => $meta_key,
            "meta_value" => $meta_value,
        );

        $db->insert("property", $data);

        $result['info'] = ShQuery::lastInsert("*", "property");
    } catch (Exception $e) {
        Func::error($e->getMessage(), 400); //"Something is wrong in your insert. Check in your values"
    }
});


$router->put('/property', function () {
    global $result, $db, $_post, $_get;

    $id = @$_post->id;
    $meta_value = @$_post->meta_value;

    if (empty($id) || empty($meta_value))
        Func::error("Your id or value is null", 400);

    try {
        $data = array(
            "meta_value" => @$meta_value
        );

        $db->update("property", $data, "id = ? and restaurant_id = ?", array($id, RESTAURANT_ID));

        $result['info'] = $db->select("*")->from("property")->Where("id = ? and restaurant_id = ?", array($id, RESTAURANT_ID))
            ->limit(1)
            ->execute()
            ->fetchObject();
    } catch (Exception $e) {
        Func::error($e->getMessage(), 400); //"Something is wrong in your insert. Check in your values"
    }
});


$router->delete('/property', function () {
    global $result, $db, $_get;

    $id = @$_get->id;

    if (empty($id))
        Func::error("Your id is null", 400);

    try {
        $deleted = $db->delete("property", "id = ? and restaurant_id = ?", array($id, RESTAURANT_ID));
    } catch (Exception $e) {
        Func::error($e->getMessage(), 400);
    }
});
