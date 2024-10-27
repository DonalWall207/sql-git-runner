<?php
$user_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = " . $user_id; // Vulnerable to SQL Injection
mysql_query($query);
?>

