<?php
class Menu
{

    private static $db;

    public static function init($db)
    {
        self::$db = $db;
    }


    public static function getProperty($meta_key)
    {
        try {
            return self::$db->select('id,meta_value')
                ->from("property")
                ->where("restaurant_id=? and meta_key=?", array(RESTAURANT_ID, $meta_key))
                ->orderBy("id desc")
                ->execute()->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            Func::error($e->getMessage(), 400);
        };
    }


    public static  function getCategory($is_option)
    {

        try {
            $items =  self::$db->select('id,name,image')
                ->from("category")
                ->where("restaurant_id=? and is_option=?", array(RESTAURANT_ID, $is_option))
                ->orderBy("position asc,id desc")
                ->execute()
                ->fetchAll(PDO::FETCH_ASSOC);

            return  Func::setArray($items, 'image', BASE_URL, 'left');
        } catch (PDOException $e) {
            Func::error($e->getMessage(), 400);
        };
    }

    public static  function getProductByCategoryId($categoryId)
    {
        try {
            $items =  self::$db->select('p.id,p.name,p.price,p.status,p.description,pi.value as image')
                ->from("product p")
                ->where("category_id=?", $categoryId)
                ->join("LEFT JOIN product_image pi ON p.main_image_id = pi.id ")
                ->orderBy("p.position asc,p.id desc")
                ->execute()
                ->fetchAll();

            return  Func::setArray($items, 'image', BASE_URL, 'left');
        } catch (PDOException $e) {
            Func::error($e->getMessage(), 400);
        };
    }

    public static  function getAllProductByCategory($is_option = 0)
    {
        try {
            $categories = self::getCategory($is_option);
            foreach ($categories as $index => $category) {
                $categories[$index]["products"] = self::getProductByCategoryId($category["id"]);
            }
            return  $categories;
        } catch (PDOException $e) {
            Func::error($e->getMessage(), 400);
        };
    }
}
