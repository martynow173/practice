<?php session_start();
include "connect.php";
?>
<html lang="en">
<head>
    <meta charset="UTF-8" <?php if(isset($_POST['delete'])) {
        //$t = $db->query("SELECT image FROM products WHERE prod_id = ".$_POST['prod_id']);
        $stmt = $db->prepare("SELECT image FROM products WHERE prod_id =?");
        $stmt->bind_param("i", $_POST['prod_id']);
        $stmt->execute();
        $t = $stmt->get_result();
        //$res = $db->query("DELETE FROM products WHERE prod_id = ".$_POST['prod_id']);
        $stmt1 = $db->prepare("DELETE FROM products WHERE prod_id =?");
        $stmt1->bind_param("i", $_POST['prod_id']);
        $stmt1->execute();
        $res = $stmt1->get_result();
        if ($stmt->errno || $stmt1->errno)
        {
            exit ("Sql query error");
        }
        $stmt1->close();
        $stmt->close();
        $path = mysqli_fetch_array($t);
        unlink("D:/OSPanel/domains/test/".$path['image']);
        echo '
        http-equiv="refresh" content="0"
        ';
    }
    ?>  >
    <title>table</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        if ($_SESSION['rights'] == 1){ echo ("<p>Вы администратор</p><br>
            <a href='logout.php'>Выйти</a><br>
            <br><a href='addp.php'> Добавить продукт</a><br><br>");

        }
        elseif (!isset($_SESSION['rights'])) {echo ("<p>Вы не авторизованы</p><a href='index.html'>Войти</a><br>");}
        else {
            echo "<p>Вы пользователь</p><br><a href='logout.php'>Выйти</a>";
        }

    ?>
<br>
    <table>
    <thead>
           <td>
               Изображение
           </td>
           <td>
               Название
           </td>
           <td>
               Описание
           </td>
           <td>
               Категория
           </td>
    </thead>
    <tbody>
    <?php
    /*if (!$result = $db->query("SELECT * FROM products INNER JOIN classlist using(class_id)")) {
        exit ("Sql query error");

    }*/
    $stmt = $db->prepare("SELECT * FROM products INNER JOIN classlist using(class_id)");
    $stmt->execute();
    if($stmt->errno) {
        exit ("Sql query error");
    } else {
        $result = $stmt->get_result();
        $stmt->close;
    }
    while ($t = mysqli_fetch_array($result)) {
        $path = $t['image'];
        echo '
            <tr> 
                <td> <img src=' . $path . ' width="70" height="50" alt="Изображение"></td>
                <td><a href="prod_full_page.php?a='.$t['prod_id'].'">' . $t['name'] . '</a></td>
                <td>' . $t['description'] . '</td>
                <td>' . $t['class_name'] . '</td>';
            if ($_SESSION['rights'] == 1) {
                $id = $t['prod_id'];
                echo '<td style="border: 0">
                    <form name="delete" method="POST"> 
                        <input name="prod_id" value=' . $id . ' type="hidden">
                        <input type="submit" name="delete" value="удалить">
                    </form> 
                </td>
                <td style="border: 0">
                    <form name="edit" method="POST" action="edit_prod_info.php">
                        <input name="prod_id" value='.$id.' type="hidden"> 
                        <input name="edit" type="submit" value="редактировать">
                    </form>
                </td>';
            }
        }
        echo '</tr>';
    ?>
    </tbody>
    </table>
</body>
</html>