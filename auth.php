<?php
session_start();

if(!isset($_SESSION['website_login']))
{
    header("Location: login.php");
    exit();
}
?>