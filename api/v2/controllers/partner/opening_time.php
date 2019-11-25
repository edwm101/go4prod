<?php




$router->get('all', function () {
    global  $db, $result;
    try {
        //Opening time
        $opening_time = $db->select('id,wday,status,start_time,end_time')
            ->from("opening_time")
            ->where("restaurant_id=?", RESTAURANT_ID)
            ->orderBy("start_time desc")
            ->execute()->fetchAll();
        $group_opening_time = [];
        foreach ($opening_time as $key => $value) {
            $group_opening_time[$value['wday']][] = $value;
        }
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
        $opening_time = [];
        foreach ($days as $key => $day) {
            $opening_time[$key]["day"] = $day;
            $opening_time[$key]["start_time_edit"] = "00:00";
            $opening_time[$key]["end_time_edit"] = "23:59";
            $opening_time[$key]["open_edit"] = false;

            if (isset($group_opening_time[$key + 1])) {
                $opening_time[$key]["items"] = $group_opening_time[$key + 1];
            } else {
                $opening_time[$key]["items"] = [];
            }
        }

        $result["opening_time"] = $opening_time;
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});


$router->post('', function () {
    global $result, $db, $_post;
    $dz = new OpeningTime($_post);
    Func::emptyCheck([$dz->get_start_time(), $dz->get_end_time()]);
    try {

        if ($dz->get_start_time() >=  $dz->get_end_time())
            Func::error("TIME_ERROR_BIG", 400);

        $db->count("opening_time", "restaurant_id=? and wday=? and start_time=? and end_time=?", [
            RESTAURANT_ID, $dz->get_wday(),
            $dz->get_start_time(), $dz->get_end_time()
        ]) && Func::error("ALREADY_EXISTS", 400);

        $opening_time_checks = $db->select('id,status,wday,start_time,end_time')
            ->from("opening_time")
            ->where("restaurant_id=? and wday=?", [RESTAURANT_ID, $dz->get_wday()])
            ->orderBy("id desc")
            ->execute()->fetchAll();

        $items = [];
        foreach ($opening_time_checks as $item) {
            if ($dz->get_start_time() > $item['start_time'] &&  $dz->get_start_time() < $item['end_time'])
                Func::error("TIME_ERROR_INTERVAL", 400);
            if ($dz->get_end_time()  > $item['start_time'] &&  $dz->get_end_time()  < $item['end_time'])
                Func::error("TIME_ERROR_INTERVAL", 400);

            if ($dz->get_start_time()  <= $item['start_time'] &&  $dz->get_end_time()  >= $item['end_time'])
                $db->delete("opening_time", "id=?", $item['id']);

            $items[] = $item;
        }

        $data = [
            "restaurant_id" => RESTAURANT_ID,
            "wday" => $dz->get_wday(),
            "status" => 1,
            "start_time" => $dz->get_start_time(),
            "end_time" => $dz->get_end_time(),
        ];
        $db->insert("opening_time", $data);
        $result["info"] = ShQuery::lastInsert("*", "opening_time");
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    }
});



$router->put('', function () {
    global $result, $db, $_post;
    $dz = new OpeningTime($_post);
    Func::emptyCheck([$dz->get_id(), $dz->get_start_time(), $dz->get_end_time()]);
    try {

        if ($dz->get_start_time() >=  $dz->get_end_time())
            Func::error("TIME_ERROR_BIG", 400);

        $db->count("opening_time", "id!=? and restaurant_id=? and wday=? and start_time=? and end_time=?", [
            $dz->get_id(),
            RESTAURANT_ID, $dz->get_wday(),
            $dz->get_start_time(), $dz->get_end_time()
        ]) && Func::error("ALREADY_EXISTS", 400);

        $opening_time_checks = $db->select('start_time,end_time')
            ->from("opening_time")
            ->where("id!=? and restaurant_id=? and wday=?", [$dz->get_id(), RESTAURANT_ID, $dz->get_wday()])
            ->orderBy("id desc")
            ->execute()->fetchAll();
        foreach ($opening_time_checks as $item) {
            if ($dz->get_start_time() >= $item['start_time'] &&  $dz->get_start_time() <= $item['end_time'])
                Func::error("TIME_ERROR_INTERVAL", 400);
            if ($dz->get_end_time()  >= $item['start_time'] &&  $dz->get_end_time()  <= $item['end_time'])
                Func::error("TIME_ERROR_INTERVAL", 400);
        }


        $data = [
            "start_time" => $dz->get_start_time(),
            "end_time" => $dz->get_end_time(),
        ];
        $db->update("opening_time", $data, "id = ? and restaurant_id = ?", array($dz->get_id(), RESTAURANT_ID));
        $result['info'] = $db->select("*")->from("opening_time")->Where("id = ? and restaurant_id = ?", array($dz->get_id(), RESTAURANT_ID))
            ->limit(1)
            ->execute()
            ->fetchObject();
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    }
});

$router->put('status', function () {
    global $result, $_get;
    $result["opening_time"] = ShQuery::changeStatus("opening_time", $_get->id, RESTAURANT_ID);
});

$router->delete('', function () {
    global  $db, $_get;
    $id = @$_get->id;
    ShQuery::delete("opening_time", $id, RESTAURANT_ID);
});
