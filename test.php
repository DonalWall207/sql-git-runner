<?php
// vulnerable.php

// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input (e.g., from a form or URL parameter)
$user_id = $_GET['user_id']; // User input without sanitization

// Constructing SQL query directly with user input
$sql = "SELECT * FROM users WHERE id = " . $user_id;

// Execute the query
$result = $conn->query($sql);

// Check for results
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
    }
} else {
    echo "No results found.";
}

// Close connection
$conn->close();
?>

