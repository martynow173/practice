<?php
include "connect.php";
?>

<html>
<head>
    <title>Редактирвоание товара</title>
</head>
<meta   <?php
if (isset($_POST['save_ed_data'])) {
    $nm = trim(htmlspecialchars($_POST['name']));
    $descr= trim(htmlspecialchars($_POST['description']));
    $imgpath = $_POST['imagepath'];
    $pid = $_POST['prod_id'];
    $cid = $_POST['ed_classlist'];
    $nip = $_POST['newimagepath'];

    if (isset($_FILES['newimage']) and strlen($_FILES['newimage']['name']) > 0) {

        $image_name = $_FILES['newimage']['name'];
        $image_name = md5( microtime() . mt_rand() ).".jpg";
        unlink("D:/OSPanel/domains/test/".$imgpath);
        $folder = "D:/OSPanel/domains/test/images/";

        $image_path = "$folder".$image_name;
        move_uploaded_file($_FILES['newimage']['tmp_name'], $image_path);
        $spath= substr($image_path, 24);
        //$q = $db->query("UPDATE products SET image='$spath' WHERE prod_id='$pid'");
        $stmt=$db->prepare("UPDATE products SET image=? WHERE prod_id=?");
        $stmt->bind_param("si",$spath, $pid);
        $stmt->execute();
        $res = $stmt->get_result();
        if($stmt->errno) {
            exit ("Sql query error");

        }
        else {
            $stmt->close();

        }


    }
    //$q1 = $db->query("UPDATE products SET name='$nm', description='$descr', class_id='$cid' WHERE prod_id='$pid'");
    $stmt1=$db->prepare("UPDATE products SET name=?, description=?, class_id=? WHERE prod_id=?");
    $stmt1->bind_param("ssii", $nm, $descr, $cid, $pid);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    if ($stmt1->errno ) {
        exit ("Sql query error");
    } else {
        $stmt1->close();
        //var_dump($_FILES['newimage']);
        echo '
           http-equiv="refresh" content="0; table.php
    ';

    }
}

?>">
<body>
<h2>Редактирвоание</h2>
<?php
    if (isset($_POST['edit'])) {
        $id = $_POST['prod_id'];
        /*if ($q = $db->query("SELECT * FROM products WHERE prod_id =" . $id)) {
            $prod = mysqli_fetch_array($q);

        } else {
            exit ("Sql query error");
        }*/
        $stmt = $db->prepare("SELECT * FROM products WHERE prod_id =?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if($stmt->errno) {
            exit ("Sql query error");

        }else {
            $res = $stmt->get_result();
            $prod = mysqli_fetch_array($res);
            $stmt->close();
        }

    }else {
        $id = $_POST['prod_id'];
        $prod['name'] = $_POST['name'];
        $prod['description'] = $_POST['description'];
        $prod['class_id'] = $_POST['class_id'];
        $prod['image'] = $_POST['imagepath'];
    }
    echo '<h3>Редактирование товара №' .$id.'</h3>';

    echo '
    <form name="edited_data" method="POST" enctype="multipart/form-data">
          <input name="prod_id" value='.$id.' type="hidden">
          <p>Название</p>
          <input name="name" value=' . $prod['name'] . ' ><br>
          <p>Описание</p>
          <input name="description" value=' . $prod['description'] . ' ><br>
          <p>Категория</p>
          <select name = "ed_classlist">
          ';

    /*if (!$cl = $db->query("SELECT * from classlist")) {
        exit ("Sql query error");

    }*/
    $stmt = $db->prepare("SELECT * from classlist");
    $stmt->execute();
    if($stmt->errno) {
        exit ("Sql query error");

    } else {
        $cl = $stmt->get_result();
        $stmt->close();
    }

    while ($c = mysqli_fetch_array($cl)) {

        $classname = $c['class_name'];
        $class_id = $c['class_id'];
        echo '<option value = ' . $class_id;
        if ($c["class_id"] == $prod["class_id"]) {
            echo ' selected';
        }
        echo '>' . $classname . '</option>';
    }
    echo '</select><br>
          <p>Текущее изображение</p>
          <img src="' . $prod['image'] . '"  width="200" height="150" alt="Изображение" > <br>
          <input name="imagepath" type="hidden" value ="'.$prod['image'].'"> <br>
          <input name="newimage" type="file"><br><br>
          <input  name="save_ed_data" type = "submit" value="Сохранить"><br><br>
          <a href="table.php">Вернуться</a>
    </form>
';




?>

</body>
</html>
