<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from a GET request
$user_id = $_GET['id'];

// Unsafe SQL query susceptible to SQL injection
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

// Check if any results were returned
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
    }
} else {
    echo "0 results";
}

// Close the connection
$conn->close();
?>

