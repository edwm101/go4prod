<?php

App::get('all', function () {
    App::$response["items"] = App::db()
        ->select('*')
        ->from("providers")
        ->execute()->fetchAll();
});


App::get('', function () {
    $max = App::$request["max"] ?? 10;
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);

    App::$response["info"] = App::db()->select('*')
        ->from("providers p")
        ->orderBy("p.id desc")
        ->execute()->fetchObject();

    App::$response["items"] = App::db()->select('p.id,p.name,p.price,p.image,c.name as cat_name')
        ->from("products p")
        ->join("left join categories c on c.id = p.category_id ")
        ->join("left join providers pr on pr.id = p.provider_id ")
        ->orderBy("p.id desc")
        ->where("pr.id=?", $id)
        ->limit($max)
        ->execute()->fetchAll();
});
