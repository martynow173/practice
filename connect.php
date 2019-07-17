<?php
//$db  = mysql_connect("localhost", "mysql", "mysql");
//mysql_select_db("test", $db);
$db = mysqli_connect("localhost", "mysql", "mysql", "test");
if(!$db) {
    die('Соединение не удалось: Код ошибки: '.mysqli_connect_errno().' - '.mysqli_connect_error());
}

?>