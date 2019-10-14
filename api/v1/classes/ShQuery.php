<?php
// Shared query between object
class ShQuery
{

    // database connection 
    public static $db;

    public static function init($connection)
    {
        self::$db = $connection;
    }

    // Filter input data
    public static function fHtml($value)
    {
        return htmlspecialchars(strip_tags($value));
    }

    public static function fHash($value)
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }


    /**
     * pagination in one table - very simple implementation
     *
     * @param string $statement
     * @param string $table
     * @param mixed $where
     * @param array $where_params
     * @return object
     */
    public static function lastInsert($statement = "*", $table_name)
    {
        $last_id = self::$db->lastInsertId();
        return self::$db->select($statement)->from($table_name)->Where("id=?", $last_id)->limit(1)->execute()->fetchObject();
    }

    public static function info($statement = "*", $table_name, $id)
    {
        return self::$db->select($statement)->from($table_name)->Where("id=?", $id)->limit(1)->execute()->fetchObject();
    }

    /**
     * pagination in one table - very simple implementation
     *
     * @param string $statement
     * @param string $table
     * @param mixed $where
     * @param array $where_params
     * @param string $max
     * @param int $from
     * @return object
     */
    public static function find($statement = "*", $table_name, $where, $where_params = null, $orderBy, $max = 50, $current_page = 1)
    {
        $max == 0 ? $max = 1 : $max;

        //Pagination
        $total_rows   =  ceil(self::$db->count("$table_name", $where, $where_params));
        $total_pages  =  ceil($total_rows / $max);
        $from =  ceil(($current_page - 1) * $max);

        $stmt = self::$db->select($statement)
            ->from($table_name)
            ->Where($where, $where_params)
            ->orderBy($orderBy)
            ->limit("$from,$max")->execute();

        $num = $stmt->rowCount();

        return array(
            "detail" => array(
                "count" => $num,
                "total" => $total_rows,
                "pages" => $total_pages
            ),
            "items" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }
}
