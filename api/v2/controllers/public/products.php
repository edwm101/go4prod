<?php


App::get('all', function () {
    $max = App::$request["max"] ?? 10;
    App::$response["items"] = App::db()
        ->select('p.id,p.nam,p.price,p.image,c.name as cat_name')
        ->from("products p")
        ->join("left join categories c on c.id = p.category_id ")
        ->orderBy("p.id desc")
        ->limit($max)
        ->execute()->fetchAll();
});

App::get('', function () {
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    $info =  App::db()->xQuery("select * from products where id=?", $id)->fetchObject();
    $info->images =   explode(",", str_replace('small_default', 'large_default', $info->images));
    App::$response["info"] = $info;
});


App::get('find', function () {

    $max = App::$request["max"] ?? 10;
    $cp = App::$request["cp"] ?? 0;
    $q = App::$request["q"] ?? "jbl";
    $q = str_replace("+", " ", $q);
    $sortby = App::$request["sortby"] ?? "price";
    $sort_type = App::$request["sort_type"] ?? "desc";
    $min_price = App::$request["min_price"] ?? 0;
    $max_price = App::$request["max_price"] ?? 10000000000;

    $req  =  App::db()->select('*')
        ->from("products")
        ->where(
            " (LOWER(name) LIKE LOWER(concat('%',?, '%'))
         or LOWER(short_description) LIKE LOWER(concat('%',?, '%')) ) and price>=? and price < ?  ",
            [$q, $q, $min_price, $max_price]
        )
        ->orderBy($sortby . "  " . $sort_type);

    $total_rows = $req->execute()->rowCount();
    $total_pages  =  ceil($total_rows / $max);
    $from =  ceil(($cp) * $max);

    $items = $req->limit("$from,$max")->execute();

    App::$response["detail"]  = [
        "q" => $q,
        "count" => $items->rowCount(),
        "total" => $total_rows,
        "pages" => $total_pages
    ];
    App::$response["items"] = $items->fetchAll();
});
