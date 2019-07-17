<?php
    include "connect.php";

?>
<html>
<head>
    <title>Описание товара</title>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<?php
    $stmt = $db->prepare("SELECT * FROM products INNER JOIN classlist using(class_id) WHERE prod_id =?");
    $stmt->bind_param("i", $_GET['a']);
    $stmt->execute();
    $q = $stmt->get_result();
    $stmt->close();
    $info = mysqli_fetch_array($q);
    echo '<h2>Продукт №'.$_GET['a'].'
          </h2><h4>Наименование: "'.$info['name'].'"</h4>
          <img src=' . $info['image'] . ' width="300" height="200" alt="Изображение"><br>
          <a href="full_image.php?link='.$info['image'].'" target="_blank">Открыть в полном размере</a>
          <h4>Описание: </h4><p>'.$info['description'].'</p>
          <h4>Категория: </h4><p>'.$info['class_name'].'</p>
          <br><a href="table.php">Вернуться</a>
            
    
    ';



?>
</body>
</html>