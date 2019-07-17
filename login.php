<?php
if (isset($_POST['login']) and strlen($_POST['login']) > 0)
{
    $login = htmlspecialchars($_POST['login']);
    $login = trim($login);
}
else {
    unset($login);
}
if (isset($_POST['password']) and strlen($_POST['password']) > 0)
{
    $password = htmlspecialchars($_POST['password']);
    $password = trim($password);
    $password = md5($password);
}
else {
    unset($password);

}
include("connect.php");
    $stmt = $db->prepare("SELECT user_id,rights FROM users WHERE login=? and password=?");
    $stmt->bind_param("ss", $login, $password);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
if ($res and mysqli_num_rows($res) == 0) {
    exit ("Неверная пара логин-пароль");
} else {
    //$rights = $db->query("SELECT rights FROM users WHERE login='$login'");
    $user = mysqli_fetch_array($res);
    session_start();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['rights'] = $user['rights'];
    header ('Location: table.php');


}
?>