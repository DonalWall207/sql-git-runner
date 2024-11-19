<?php
// Safe PHP Code

// Initialize MySQLi connection
$mysqli = new mysqli("localhost", "user", "password", "database");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Use prepared statements for safe query execution

// Safe retrieval of user ID from GET request
if (isset($_GET['id'])) {
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']); // "i" denotes the parameter type (integer)
    $stmt->execute();
    $result = $stmt->get_result();
    // Process results...
    $stmt->close();
}

// Safe retrieval of product name from user input
if (isset($_POST['product_name'])) {
    $stmt = $mysqli->prepare("SELECT * FROM products WHERE name = ?");
    $stmt->bind_param("s", $_POST['product_name']); // "s" denotes the parameter type (string)
    $stmt->execute();
    $result = $stmt->get_result();
    // Process results...
    $stmt->close();
}

// Safe retrieval of order ID from POST request
if (isset($_POST['search'])) {
    $stmt = $mysqli->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $_POST['search']); // Assuming order_id is an integer
    $stmt->execute();
    $result = $stmt->get_result();
    // Process results...
    $stmt->close();
}

// Safe retrieval of username from cookies
if (isset($_COOKIE['username'])) {
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $_COOKIE['username']); // "s" denotes the parameter type (string)
    $stmt->execute();
    $result = $stmt->get_result();
    // Process results...
    $stmt->close();
}

// Close the database connection
$mysqli->close();
?>

