<?php
require('conn.php');
$user_id = $_POST['user_id'];

$user_answers = "SELECT * FROM UsersResults WHERE user_id = $user_id";

$result = $conn->query($user_answers);

if ($result->num_rows > 0) {
    $sql = "DELETE FROM UsersResults WHERE user_id = $user_id";
    $conn->query($sql);
}

$sql = "DELETE FROM Users WHERE id = $user_id";
if($conn->query($sql))
{
    header("Location: ../pages/_admin/employees.php");
}