<?php




$router->get('all', function () {
    global  $db, $result;
    try {
        $result["items"] = $db->select('*')
            ->from("delivery_zone")
            ->where("restaurant_id=?", array(RESTAURANT_ID))
            ->orderBy("id desc")
            ->execute()->fetchAll();
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});

$router->get('find', function () {
    global  $db, $result, $_get;

    $q = $_get->q;
    $q = str_replace("+", " ", $q);

    try {
        $items = $db->select('CONCAT(UPPER(SUBSTRING(city,1,1)),LOWER(SUBSTRING(city,2))) AS city,zip_code')
            ->from("zip_code")
            ->groupBy("city")
            ->where("LOWER(city) LIKE LOWER(concat('%',?, '%'))  ", $q)
            ->orderBy("LENGTH(city) asc")
            ->limit("20")->execute()->fetchAll();
        array_unshift($items, ["city" => ucwords($q), "zip_code" => null]);
        $result["items"] = $items;
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});

$router->post('', function () {
    global $result, $db, $_post;

    $dz = new DeliveryZone($_post, $db);
    Func::emptyCheck([$dz->get_zip_code(), $dz->get_city()]);

    try {
        $count = $db->count("delivery_zone", "restaurant_id=? and city=? and zip_code=?", [
            RESTAURANT_ID,
            $dz->get_city(), $dz->get_zip_code()
        ]) && Func::error("ALREADY_EXISTS", 400);

        $data = [
            "restaurant_id" => RESTAURANT_ID,
            "city" => $dz->get_city(),
            "zip_code" => $dz->get_zip_code(),
            "min_duration" => $dz->get_min_duration(),
            "max_duration" => $dz->get_max_duration(),
            "min_order" => $dz->get_min_order(),
            "shipping_cost" => $dz->get_shipping_cost(),
            "status" => 1
        ];
        $db->insert("delivery_zone", $data);
        $result["info"] = ShQuery::lastInsert("*", "delivery_zone");
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    }
});




$router->put('', function () {
    global $result, $db, $_post;

    $dz = new DeliveryZone($_post, $db);
    Func::emptyCheck([$dz->get_id(), $dz->get_zip_code(), $dz->get_city()]);

    try {

        $db->count("delivery_zone", "id!=? and restaurant_id=? and city=? and zip_code=?", [
            $dz->get_id(),
            RESTAURANT_ID,
            $dz->get_city(), $dz->get_zip_code()
        ]) && Func::error("ALREADY_EXISTS", 400);

        $data = [
            "city" => $dz->get_city(),
            "zip_code" => $dz->get_zip_code(),
            "min_duration" => $dz->get_min_duration(),
            "max_duration" => $dz->get_max_duration(),
            "min_order" => $dz->get_min_order(),
            "shipping_cost" => $dz->get_shipping_cost(),
        ];
        $db->update("delivery_zone", $data, "id = ? and restaurant_id = ?", array($dz->get_id(), RESTAURANT_ID));
        $result['info'] = $db->select("*")->from("delivery_zone")->Where("id = ? and restaurant_id = ?", array($dz->get_id(), RESTAURANT_ID))
            ->limit(1)
            ->execute()
            ->fetchObject();
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    }
});

$router->put('status', function () {
    global $result, $_get;
    $result["delivery_zone"] = ShQuery::changeStatus("delivery_zone", $_get->id, RESTAURANT_ID);
});

$router->delete('', function () {
    global  $db, $_get;
    $id = @$_get->id;
    ShQuery::delete("delivery_zone", $id, RESTAURANT_ID);
});
