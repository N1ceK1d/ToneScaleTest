<?php
require('conn.php');

$first_name = $_POST['first_name'];
$second_name = $_POST['second_name'];
$company_id = $_POST['company_id'];

$sql = "INSERT INTO Users (first_name, second_name, company_id)
VALUES ('$first_name', '$second_name', $company_id)";

if($conn->query($sql))
{
    session_start();
    $_SESSION['user_id'] = $conn->insert_id;
    header("Location: ../pages/test.php"); 
}
?>