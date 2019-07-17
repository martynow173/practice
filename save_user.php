<?php
if (isset($_POST['login']) and strlen($_POST['login']) > 0 )
{
    $login = htmlspecialchars($_POST['login']);
    $login = trim($login);
}
else {
    unset($login);
    exit ("Поля необходимо заполнить!<br><a href='register.html'>Вернуться</a>");

}
if (isset($_POST['password']) and strlen($_POST['password'] ) > 0)
{
    $password = htmlspecialchars($_POST['password']);
    $password = trim($password);
    $password = md5($password);
}
else {
    unset($password);
    exit ("Поля необходимо заполнить!<br><a href='register.html'>Вернуться</a>");
}
include("connect.php");


$stmt=$db->prepare("SELECT user_id FROM users WHERE login=?");
$stmt->bind_param("s", $login);
$stmt->execute();
$result=$stmt->get_result();
if ($stmt->errno) {
    exit ("SQL error");
} else if(mysqli_num_rows($result) > 0) {
    exit ("Логин уже зарегистрирован");
} else {
    $stmt1=$db->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
    $stmt1->bind_param("ss", $login, $password);
    $stmt1->execute();
    if ($stmt1->errno) {
        exit ("SQL query error");
    }
    session_start();
    $result = mysqli_fetch_array($result);
    $stmt2=$db->prepare("SELECT user_id, rights FROM users WHERE login=?");
    $stmt2->bind_param("s", $login);
    $stmt2->execute();
    if($stmt2->errno) {
        exit ("SQL query error");
    }
    $result1=$stmt2->get_result();
    $result1=mysqli_fetch_array($result1);
    $_SESSION['user_id'] = $result1['user_id'];
    $_SESSION['rights'] = $result1['rights'];
}
$stmt->close();
$stmt1->close();
$stmt2->close();
/*if ($result = $db->query("SELECT user_id FROM users WHERE login='$login'") and mysqli_num_rows($result) > 0) {
    exit ("Логин уже зарегистрирован");
} else {
    if (!$result1 = $db->query("INSERT INTO users (login, password) VALUES ('$login', '$password')")){
        exit ("Sql query error");
    }
}*/
header('Location: table.php');


?>
