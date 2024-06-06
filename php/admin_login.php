<?php
require('conn.php');
session_start();

$login = $_POST['admin_login'];
$password = $_POST['password'];

$sql = "SELECT * FROM Admins WHERE login = '$login'";

$res = mysqli_fetch_assoc($conn->query($sql));
if(password_verify($password, $res['password']))
{
    $_SESSION['admin_id'] = $res['id'];
    header("Location: ../pages/_admin/index.php");
} else 
{
    header("Location: ../pages/_admin/login.php?error=1");
}

