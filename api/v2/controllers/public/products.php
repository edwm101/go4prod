<?php

$router->get('all', function () {
    global  $db, $result, $_get;
    $max = $_get->max;
    try {
        $result["items"] = $db->select('*')
            ->from("products p")
            ->join("left join categories c on c.id = p.category_id ")
            ->orderBy("p.id desc")
            ->limit($max)
            ->execute()->fetchAll();
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});

$router->get('', function () {
    global  $db, $result, $_get;
    $id = $_get->id;
    try {
         $result["info"] = $db->select('*')
            ->from("products p")
            ->join("left join categories c on c.id = p.category_id ")
            ->where("p.id=?", $id)
            ->execute()->fetchObject();
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});
