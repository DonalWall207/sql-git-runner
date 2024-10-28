<?php
// Unsafe PHP Code

$unsafe_variable = $_GET['id'];

// Direct user input in SQL query
$query = "SELECT * FROM users WHERE id = " . $unsafe_variable;

// Using mysql_query directly with an unescaped variable
$result = mysql_query("SELECT * FROM products WHERE name = '$unsafe_variable'");

// Concatenating variables into an SQL query
$search = $_POST['search'];
$sql = "SELECT * FROM orders WHERE order_id = " . $search;

$mysqli = new mysqli("localhost", "user", "password", "database");
$query2 = "SELECT * FROM users WHERE username='" . $_COOKIE['username'] . "'";
$mysqli->query($query2);
?>

