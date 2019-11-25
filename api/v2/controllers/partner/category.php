<?php


$router->get('', function () {
    global $db, $result, $_get;

    $is_option = @$_get->is_option ?? 0;

    try {
        $items =  $db->select('id,name,image')
            ->from("category")
            ->where("restaurant_id=? and is_option=?", array(RESTAURANT_ID, $is_option))
            ->orderBy("position asc,id desc")
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);

        $items =  Func::setArray($items, 'image', '', 'left');
    } catch (PDOException $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };

    $result["items"] = $items;
});

$router->post('', function () {
    global $result, $db, $_post, $_get;

    $name = @$_post->name;
    $image = @$_post->image;
    $is_option = @$_get->is_option ?? 0;

    try {
        $count = $db->count("category", "restaurant_id=? and is_option=? and name=?", array(RESTAURANT_ID, $is_option, $name));
        if ($count  > 0)
            Func::error("ALREADY_EXISTS", 400, "");

        //Upload Image
        if (!empty($image)) {
            $image_name    =  Func::urlMix(@$name, 10, 10);
            $image_dir     =  UPLOAD_PATH . 'images/';
            $image_url     =  Func::upload($image, $image_name, $image_dir);
        }

        $data = array(
            "restaurant_id" => RESTAURANT_ID,
            "name" => $name,
            "image" => @$image_url,
            "is_option" => $is_option,
            "position" => 0,
        );

        $db->insert("CATEGORY", $data);

        $info = ShQuery::lastInsert("*", "category"); // return object {id:..}

        if (!empty($info->image))
            $info->image = BASE_URL . $info->image;

        $result['info'] = $info;
    } catch (Exception $e) {
        if (!empty($image_url)) unlink($image_url);
        Func::error("PDO_EXCEPTION", 400, $e->getMessage()); //"Something is wrong in your insert. Check in your values"
    }
});

$router->put('', function () {
    global $result, $db, $_post;
    $id    = @$_post->id;
    $name  = @$_post->name;
    $image = @$_post->image;


    Func::emptyCheck([$id, $name]);

    try {
        $image_remove =  $db->select('image')
            ->from("category")
            ->where("id=? and restaurant_id=?", [$id, RESTAURANT_ID])
            ->execute()
            ->fetchColumn();
        if ($image_remove) unlink($image_remove);

        //Upload Image
        if (!empty($image)) {
            $image_name    =  Func::urlMix(@$name, 10, 10);
            $image_dir     =  UPLOAD_PATH . 'images/';
            $image_url     =  Func::upload($image, $image_name, $image_dir);
        }

        $data = array(
            "name" => $name,
            "image" => @$image_url,
        );
        $result["info"] = $data;
        $db->update("category", $data, "id = ? and restaurant_id = ?", array($id, RESTAURANT_ID));
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage()); //"Something is wrong in your insert. Check in your values"
    }
});

$router->delete('', function () {
    global $db, $_get;

    $id = @$_get->id;

    Func::emptyCheck([$id]);

    try {
        $image_url =  $db->select('image')
            ->from("category")->where("id=? and restaurant_id=?", array($id, RESTAURANT_ID))
            ->execute()
            ->fetchColumn();
        if (!(empty($image_url) || $image_url == "null"))
            unlink($image_url);


        $old_product_images = $db->select('pi.value')
            ->from("product_image pi")
            ->join("join product p on pi.product_id = p.id")
            ->where("p.category_id=?", $id)
            ->execute()
            ->fetchAll();

        foreach ($old_product_images as $key => $old_product_image) {
            unlink($old_product_image['value']);
        }

        $db->delete("category", "id =? and restaurant_id=?", array($id, RESTAURANT_ID));
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    }
});

$router->put('/status', function () {
    global $db, $result;
    $id = @$_GET["id"];

    Func::emptyCheck([$id]);

    $db->executeQuery("UPDATE category SET status= NOT status WHERE id=? and restaurant_id=?", [$id, RESTAURANT_ID]);

    $result['info'] = ShQuery::info("name,status", "category", $id);
});
