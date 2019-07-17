<?php
include "connect.php";
if (isset($_POST['name']))
{
    $name = htmlspecialchars($_POST['name']);
    $name = trim($name);
}
else {
    unset($name);
    exit ("Поля необходимо заполнить!");
}
if (isset($_POST['description']))
{
    $description = htmlspecialchars($_POST['description']);
    $description = trim($description);
}
else {
    unset($description);
    exit ("Поля необходимо заполнить!");
}
if (isset($_POST['classlist']))
{
    $classlist = $_POST['classlist'];
}
else {
    unset($classlist);
    exit ("Поля необходимо заполнить!");
}

if(isset($_FILES['image'])) {
    //$image_name = $_FILES['image']['name'];
    $image_name = md5( microtime().mt_rand()).".jpg";

    $folder = "D:/OSPanel/domains/test/images/";
    $image_path = "$folder".$name.$image_name;
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
} else {
    exit ("Поля необходимо заполнить!");
}

$image_path = substr($image_path, 24);
/*if (!$result1 = $db->query("INSERT INTO products (name, description, class_id, image) VALUES ('$name', '$description', '$classlist', '$image_path')")){
    exit ("Sql query error");

}*/
$stmt=$db->prepare("INSERT INTO products (name, description, class_id, image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $name, $description, $classlist, $image_path);
$stmt->execute();
$result1 = $stmt->get_result();
if ($stmt->errno) {
    exit ("SQL error");
}
$stmt->close();
header('Location: table.php');
?>