<?php


$router->get('info', function () {
    global $db, $result;

    $result["category"] =  $db->select('id,name')
        ->from("category")
        ->where("restaurant_id=? and is_option=?", array(RESTAURANT_ID, 0))
        ->orderBy("position asc,id desc")
        ->execute()
        ->fetchAll();

    $result["option"] =  $db->select('id,name')
        ->from("category")
        ->where("restaurant_id=? and is_option=?", array(RESTAURANT_ID, 1))
        ->orderBy("position asc,id desc")
        ->execute()
        ->fetchAll();

    $result["tag"] = $db->select('id,meta_value')
        ->from("property")
        ->where("restaurant_id=? and meta_key=?", array(RESTAURANT_ID, "tag"))
        ->orderBy("id desc")->execute()->fetchAll();

    $result["allergen"] = $db->select('id,meta_value')
        ->from("property")
        ->where("restaurant_id=? and meta_key=?", array(RESTAURANT_ID, "allergen"))
        ->orderBy("id desc")->execute()->fetchAll();
});

$router->get('all', function () {
    global $db, $result, $_get;

    $is_option = $_get->is_option ?? 0;

    try {
        $categories =  $db->select('id,name,image')
            ->from("category")
            ->where("restaurant_id=? and is_option=?", array(RESTAURANT_ID, $is_option))
            ->orderBy("position asc,id desc")
            ->execute()
            ->fetchAll();
        $categories =  Func::setArray($categories, 'image', '', 'left');


        foreach ($categories as $index => $category) {
            $categoryId = $category["id"];

            $categories[$index]["products"] =  $db->select('p.id,p.name,p.price,p.status,p.description,pi.value as image')
                ->from("product p")
                ->where("category_id=?", $categoryId)
                ->join("LEFT JOIN product_image pi ON p.main_image_id = pi.id ")
                ->orderBy("p.position asc,p.id desc")
                ->execute()
                ->fetchAll();

            $categories[$index]["products"] =  Func::setArray($categories[$index]["products"], 'image', '', 'left');
        }
        $result["items"] =  $categories;
    } catch (PDOException $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };
});


$router->get('/', function () {
    global $result, $db, $_get;

    $id = $_get->id;

    try {

        $info =  $db->select('p.id, p.category_id as category_id,c.name as category_name,c.image as category_image, p.name, p.description, p.price, p.short_description, p.weight, p.part_count, p.person_count, p.calorie, p.kind, p.tva_id')
            ->from("product p")
            ->join("join category c on c.id = p.category_id")
            ->where("p.id=? and c.restaurant_id=?", [$id, RESTAURANT_ID])
            ->limit(1)
            ->execute();


        if ($info->rowCount() > 0) {
            $result['info'] = $info->fetchObject();

            $result['allergen'] = getProperty('allergen', $id);

            $result['tag'] = getProperty('tag', $id);

            $result['image'] = Func::setArray($db->select('id, value as path')
                ->from("product_image")
                ->where("product_id=?", $id)
                ->orderby("id")
                ->execute()
                ->fetchAll(), 'path', '', 'left');

            $option = $db->select('po.option_id as id,c.name')
                ->from("product_option po")
                ->join("INNER JOIN category c ON po.option_id = c.id")
                ->where("product_id=?", $id)
                ->orderBy("name desc")
                ->execute()
                ->fetchAll();
            $current_option = null;
            foreach ($option as $key => $item) {
                if (str_replace('+', '', $current_option) == $item['name']) {
                    $item['name'] =  $current_option . "+";
                }
                $current_option =  $item['name'];
                $option[$key] = $item;
            }

            $result['option'] = $option;
        } else {
            Func::error("NOT_EXIST", 404);
        }
    } catch (PDOException $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    };

    //$result["items"] = [$product_data, $product_option, $product_tag, $product_allergen, $product_image];
});


$router->post('/', function () {

    global $result, $db, $_post, $_get;

    $is_option = @$_post->is_option;
    $category_id = @$_post->category_id;
    $name = @$_post->name;
    $description = @$_post->description;
    $price = @$_post->price ?? 0;
    $short_description = @$_post->short_description;
    $weight = @$_post->weight;
    $part_count = @$_post->part_count;
    $person_count = @$_post->person_count;
    $calorie = @$_post->calorie;
    $kind = @$_post->kind;
    $tva_id = @$_post->tva_id;

    $options = $_post->option ?? [];
    $allergens = $_post->allergen ?? [];
    $tags = $_post->tag ?? [];
    $product_images = $_post->product_image  ?? [];


    Func::emptyCheck([$category_id, $name, $tva_id]);

    try {


        $db->select("p.name")
            ->from("product p")
            ->join("INNER JOIN category c")
            ->where("c.restaurant_id = ? and c.id = p.category_id and lower(p.name) = lower(?) and c.is_option = ?", [RESTAURANT_ID, $name, $is_option])
            ->limit(1)
            ->execute()
            ->fetchColumn() &&  Func::error("ALREADY_EXISTS", 400);


        $product_data = [
            "restaurant_id" => RESTAURANT_ID,
            "category_id" => $category_id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "short_description" => $short_description,
            "weight" => $weight,
            "part_count" => $part_count,
            "person_count" => $person_count,
            "calorie" => $calorie,
            "kind" => $kind,
            "tva_id" => $tva_id,
            "position" => 1,
            "status" => 1,
        ];

        $db->insert("product", $product_data);

        $product_id = $db->lastInsertId();

        setProperty($allergens, $product_id);
        setProperty($tags, $product_id);


        if ($is_option == 0) {
            setOption($options, $product_id);
        }

        //Upload Image
        setImage($product_images, $product_id, $name);

        $result['info'] = ShQuery::info("*", "product", $product_id);
    } catch (PDOException $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage()); //"Something is wrong in your insert. Check in your values"
    }
});


$router->put('status', function () {
    global $db, $result, $_get;
    $product_id = $_get->product_id;

    Func::emptyCheck([$product_id]);

    if ($db->executeQuery("UPDATE product SET status= NOT status WHERE id=? and restaurant_id = ?", [$product_id, RESTAURANT_ID])->rowCount() > 0) {
        $result['info'] = ShQuery::info("name,status", "product", $product_id);
    }
});

$router->put('/', function () {
    global $result, $db, $_post;

    $product_id = @$_post->product_id;
    $is_option = @$_post->is_option;
    $category_id = @$_post->category_id;
    $name = @$_post->name;
    $description = @$_post->description;
    $price = @$_post->price ?? 0;
    $short_description = @$_post->short_description;
    $weight = @$_post->weight;
    $part_count = @$_post->part_count;
    $person_count = @$_post->person_count;
    $calorie = @$_post->calorie;
    $kind = @$_post->kind;
    $tva_id = @$_post->tva_id;
    $options = $_post->option ?? [];
    $allergens = $_post->allergen ?? [];
    $tags = $_post->tag ?? [];
    $product_images = $_post->product_image  ?? []; // if(!isset( $_post->product_imag)) { $product_imag = []}


    Func::emptyCheck([$category_id, $name, $tva_id]);

    try {

        $db->select("p.name")
            ->from("product p")
            ->join("INNER JOIN category c")
            ->where("p.id!=? and c.restaurant_id = ? and c.id = p.category_id and lower(p.name) = lower(?) and c.is_option = ? ", [$product_id, RESTAURANT_ID, $name, $is_option])
            ->limit(1)
            ->execute()
            ->fetchColumn() &&  Func::error("ALREADY_EXISTS", 400);


        $product_data = [
            "category_id" => $category_id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "short_description" => $short_description,
            "weight" => $weight,
            "part_count" => $part_count,
            "person_count" => $person_count,
            "calorie" => $calorie,
            "kind" => $kind,
            "tva_id" => $tva_id,
        ];

        if ($db->count("product", "id=? and restaurant_id=?", [$product_id, RESTAURANT_ID]) > 0) {

            $db->update("product", $product_data, "id=? and restaurant_id=?", [$product_id, RESTAURANT_ID]);

            $db->delete("product_property", "product_id=?", $product_id);
            setProperty($allergens, $product_id);
            setProperty($tags, $product_id);

            if ($is_option == 0) {
                $db->delete("product_option", "product_id=?", $product_id);
                setOption($options, $product_id);
            }

            //Delete Image
            $old_product_images = $db->select('*')
                ->from("product_image")
                ->where("product_id=?", $product_id)
                ->execute()
                ->fetchAll();

            setImage($product_images, $product_id, $name);
            deleteImage($product_id, $old_product_images);
        }
    } catch (PDOException $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage()); //"Something is wrong in your insert. Check in your values"
    }
});

$router->delete('/', function () {
    global $result, $db, $_post;

    $product_id = @$_post->product_id;


    Func::emptyCheck([$product_id]);

    try {
        if ($db->delete("product", "id=? and restaurant_id=?", [$product_id, RESTAURANT_ID])->rowCount() > 0) {
            $db->delete("product_property", "product_id=?", $product_id);
            $db->delete("product_option", "product_id=?", $product_id);
            deleteImage($product_id);
            $db->delete("product_image", "product_id=?", $product_id);
        }
    } catch (PDOException $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage()); //"Something is wrong in your insert. Check in your values"
    }
});

$router->put('/position', function () {
    global $db, $_post;
    $items = @$_post->items;

    Func::emptyCheck([$items]);

    foreach ($items as $index => $category) {
        $db->update("category", ["position" => $index], "id=? and restaurant_id=?", [$category->id, RESTAURANT_ID]);
        foreach ($category->products as $index2 => $product) {
            $db->update("product", ["position" => $index2], "id=? AND category_id=? and restaurant_id=?", [$product, $category->id, RESTAURANT_ID]);
        }
    }
});


//FUNCTIONS

function getProperty($meta_key, $product_id)
{
    global $db;

    return $db->select('pp.property_id as id,p.meta_value')
        ->from("product_property pp")
        ->join("JOIN property p ON pp.property_id = p.id")
        ->where("pp.product_id=? and p.meta_key =?", [$product_id, $meta_key])
        ->execute()
        ->fetchAll();
}

function setProperty($property, $product_id)
{
    global $db;

    foreach ($property as $value) {
        $to_insert = array(
            "product_id" => $product_id,
            "property_id" =>  $value->id,
        );
        $db->insert("product_property", $to_insert);
    }
}

function setImage($product_images, $product_id, $name = null)
{
    global $db;

    foreach ($product_images as $key => $product_image) {
        $image_name    =  Func::urlMix(@$name, 10, 10);
        $image_dir     =  UPLOAD_PATH . 'images/';
        $image_url     =  Func::upload($product_image->path, $image_name, $image_dir);

        if (!empty($image_url)) {
            $image_data = [
                "product_id" => $product_id,
                "value" =>  $image_url,
            ];
            $db->insert("product_image", $image_data);
            if ($key == 0) {
                $main_image_id = $db->lastInsertId();
                $main_image_data = [
                    "main_image_id" => $main_image_id
                ];
                $db->update("product", $main_image_data, "id=?", $product_id);
            }
        }
    }
}

function setOption($options, $product_id)
{
    global $db;

    foreach ($options as $option) {
        $option_data = [
            "product_id" => $product_id,
            "option_id" =>  $option->id,
        ];
        $db->insert("product_option", $option_data);
    }
}

function deleteImage($product_id, $old_product_images = "null")
{
    global $result, $db;

    if ($old_product_images == "null") {
        $old_product_images = $db->select('*')
            ->from("product_image")
            ->where("product_id=?", $product_id)
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    foreach ($old_product_images as $key => $old_product_image) {
        unlink($old_product_image['value']);
        $db->delete("product_image", "id=?", $old_product_image["id"]);
    }
}
