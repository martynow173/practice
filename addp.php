<html>
<head>
    <title>Регистрация</title>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<form action=add.php method="post" enctype="multipart/form-data">
<p><label>Название<br></label>
    <input name="name" type="text" ><br></p>
<p><label>Описание<br></label>
    <p><textarea name="description"></textarea></p>
<p><label>Выберите категорию<br></label>
    <?php
    include 'connect.php';
    echo '
    <select name = "classlist">';


    /*if (!$cl = $db->query("SELECT * from classlist")){
        exit ("SQL error");
    }*/

    $stmt= $db->prepare("SELECT * from classlist");
    $stmt->execute();
    $cl = $stmt->get_result();
    $stmt->close();
    if ($stmt->errno) {
        exit ("Sql query error");
    }
    while ($c = mysqli_fetch_array($cl)) {

        $classname = $c['class_name'];
        $class_id = $c['class_id'];
        echo '<option value = ' . $class_id . '>' . $classname . '</option>';

    }
    echo '</select>';

    ?>
</p>
<p><label>Загрузить изображение<br></label>
    <input name="image" type="file" ><br></p>
<p><input type="submit" name="submit" value="Добавить"></p>
</form>
<a href="table.php">Назад</a>
</body>
</html>