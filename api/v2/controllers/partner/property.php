<?php

$router->get('', function () {
    global  $db, $result, $_get;

    $meta_key = @$_get->meta_key;

    Func::emptyCheck([$meta_key]);

    try {
        $result["items"] = $db->select('id,meta_value')
            ->from("property")
            ->where("restaurant_id=? and meta_key=?", array(RESTAURANT_ID, $meta_key))
            ->orderBy("id desc")
            ->execute()->fetchAll();
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});

$router->post('', function () {
    global $result, $db, $_post, $_get;

    $meta_key    = @$_get->meta_key;
    $meta_value  = @$_post->meta_value;


    Func::emptyCheck([$meta_key, $meta_value]);

    try {
        $count = $db->count("property", "restaurant_id=? and meta_key=? and meta_value=?", array(RESTAURANT_ID, $meta_key, $meta_value));
        if ($count > 0)
            Func::error("ALREADY_EXISTS", 400);

        $data = array(
            "restaurant_id" => RESTAURANT_ID,
            "meta_key" => $meta_key,
            "meta_value" => $meta_value,
        );

        $db->insert("property", $data);

        $result['info'] = ShQuery::lastInsert("*", "property");
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage()); //"Something is wrong in your insert. Check in your values"
    }
});


$router->put('', function () {
    global $result, $db, $_post, $_get;

    $id = @$_post->id;
    $meta_value = @$_post->meta_value;


    Func::emptyCheck([$id, $meta_value]);

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
        Func::error("PDO_EXCEPTION", 400, $e->getMessage()); //"Something is wrong in your insert. Check in your values"
    }
});


$router->delete('', function () {
    global $result, $db, $_get;

    $id = @$_get->id;

    Func::emptyCheck([$id]);

    try {
        $deleted = $db->delete("property", "id = ? and restaurant_id = ?", array($id, RESTAURANT_ID));
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    }
});
