<?php


$router->get('all', function () {
    global $db, $result;

    try {
        $items = $db->select('id,name,logo,city,open_status')
            ->from("restaurant r")
            ->join("JOIN user_restaurant ur ON ur.restaurant_id = r.id ")
            ->where("ur.user_id=?", USER_ID)
            ->orderBy("id desc")
            ->execute()->fetchAll();
        $result["items"] =  Func::setArray($items, 'logo', '', 'left');
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});

$router->post('create', function () {
    global $db, $result, $_post;

    $name = @$_post->name;
    $slogan = @$_post->slogan;
    $logo = @$_post->logo;
    $address = @$_post->address;
    $city = @$_post->city;
    $zip_code = @$_post->zip_code;
    $tva_name = @$_post->tva_name;
    $tva_persentage = @$_post->tva_persentage;


     Func::emptyCheck([$name]);


    try {
        $image_name    =  Func::urlMix(@$name, 10, 10);
        $image_dir     =  UPLOAD_PATH . 'images/';
        $image_url     =  Func::upload($logo, $image_name, $image_dir);

        $resto_data = [
            "name" => $name,
            "slogan" => $slogan,
            "logo" => $image_url,
            "address" => $address,
            "city" => $city,
            "zip_code" => $zip_code,
            "open_status" => 0
        ];
        $db->insert("restaurant", $resto_data);
        $restaurant_id = $db->lastInsertId();

        $tva_data = [
            "restaurant_id" => $restaurant_id,
            "user_id" => USER_ID,
        ];
        $db->insert("user_restaurant", $tva_data);


        $tva_data = [
            "restaurant_id" => $restaurant_id,
            "title" => $tva_name,
            "value" => $tva_persentage,
            "default" => 1,
        ];
        $db->insert("tva", $tva_data);


        $result['info'] = ShQuery::info("*", "restaurant", $restaurant_id);
    } catch (PDOException $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage()); //"Something is wrong in your insert. Check in your values"
    }
});

$router->get('enter', function () {

    global $db, $result, $_get;

    $restaurant_id    = $_get->restaurant_id;

    if (!$data = $db->select("restaurant_id")->from("user_restaurant")->Where("restaurant_id=? and user_id=?", [$restaurant_id, USER_ID])->limit(1)->execute()->fetchObject()) {
        Func::error("INVALID_USER", 401);
    }


    $token =  Auth::authData();
    $token->{"restaurant_id"} = $restaurant_id;

    $info = $db->select("name,logo,open_status,slogan")->from("restaurant")->Where("id=?", [$restaurant_id])->limit(1)->execute()->fetchObject();
    if (!empty($info->logo)) {
        $info->logo = Func::checkImage($info->logo);
    }
    $result['info'] = $info;
    $result["token"] = Auth::createToken($token);
});
