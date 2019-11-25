<?php

$router->get('all', function () {
    global  $db, $result, $_get;
    try {
        $result["items"] = $db->select('*')
            ->from("providers p")
            ->orderBy("p.id desc")
            ->execute()->fetchAll();
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});


$router->get('', function () {
    global  $db, $result, $_get;
    $max = $_get->max;
    $id = $_get->id;
    try {
        $result["info"] = $db->select('*')
            ->from("providers p")
            ->orderBy("p.id desc")
            ->execute()->fetchObject();

        $result["items"] = $db->select('p.id,p.name,p.price,p.image,c.name as cat_name')
            ->from("products p")
            ->join("left join categories c on c.id = p.category_id ")
            ->join("left join providers pr on pr.id = p.provider_id ")
            ->orderBy("p.id desc")
            ->where("pr.id=?", $id)
            ->limit($max)
            ->execute()->fetchAll();
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});
