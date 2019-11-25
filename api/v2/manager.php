<?php

require_once './config/database.php';

function callbackhandler($matches)
{
    return strtoupper(ltrim($matches[0], "_"));
}
function convertName($name)
{
    return ucfirst(preg_replace_callback("/_[a-z]?/", "callbackhandler", $name));
}
$db     = new database\DB(DB_DSN, DB_USER, DB_PASS);

$name = $_GET["name"];
$q = $db->prepare("DESCRIBE " . $name);
$q->execute();
$table_fields = $q->fetchAll();

$firstUp = convertName($name);


$class = "<?php \n class " . $firstUp . " { " . "\n";

$class .= "private \$db; \n";
$class .= "private \$tabel_name = '" . $name . "'; \n";
foreach ($table_fields as $field) {
    $class .= "private $" . $field['Field'] . "; \n";
}


$class .= "function __construct(\$data,\$db=null){ \n";
$class .= "\$this->db= \$db; \n";

foreach ($table_fields as $field) {
    $type = explode("(", $field['Type'], 2)[0];
    $type = str_replace("tinyint", "bool", $type);
    $type = str_replace("varchar", "string", $type);
    $type = str_replace("text", "string", $type);
    $class .= "\$this->" . $field['Field'] . "= (" . $type . ") @\$data->" . $field['Field'] . "; \n";
}
$class .= "} \n";


foreach ($table_fields as $field) {
    $fieldUp = $field['Field'];
    $type = explode("(", $field['Type'], 2)[0];
    $type = str_replace("tinyint", "bool", $type);
    $type = str_replace("varchar", "string", $type);
    $type = str_replace("text", "string", $type);
    
    $class .=  "public function get_" . $fieldUp . "()  
      {
        return (" . $type . ") \$this->" . $field['Field'] . ";}";

    $class .= "public function set_" . $fieldUp . "($" . $field['Field'] . ")
    { \$this->" . $field['Field'] . " = (" . $type . ") $" . $field['Field'] . ";
    }";
}

$class .= " } " . "\n";
echo $class;

file_put_contents('entity/' . $firstUp . '.php', $class);

exit;
