<?php

$router->get('all', function () {
    global  $db, $result, $_get;
    $max = $_get->max;
    try {
        $result["items"] = $db->select('p.id,p.name,p.price,p.image,c.name as cat_name')
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
        $info = $db->select('*')
            ->from("products p")
            ->where("p.id=?", $id)
            ->execute()->fetchObject();

        $info->images =   explode(",", str_replace('small_default', 'large_default', $info->images));
        $result["info"] = $info;
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});


$router->get('find', function () {
    global  $db, $result, $_get;

    $q = $_get->q;
    $q = str_replace("+", " ", $q);

    try {
        $items = $db->select('*')
            ->from("products")
            ->where("LOWER(name) LIKE LOWER(concat('%',?, '%'))  ", $q)
            ->orderBy("LENGTH(name) asc")
            ->limit("20")->execute()->fetchAll();
        $result["query"] = ucfirst($q);
        $result["items"] = $items;
    } catch (Exception $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});
