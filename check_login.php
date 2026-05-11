<?php
session_start();

$fixed_username = "mani";
$fixed_password = "123456";

$username = $_POST['username'];
$password = $_POST['password'];

if($username == $fixed_username && $password == $fixed_password)
{
    $_SESSION['logged_in'] = true;

    header("Location: index.php");
}
else
{
    header("Location: login.php?error=1");
}
?>