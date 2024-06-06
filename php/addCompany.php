<?php 
require("conn.php");

$conn->query("INSERT INTO Companies (name) VALUES ('".$_POST['name']."')");

header("Location: ../pages/_admin/companies.php");