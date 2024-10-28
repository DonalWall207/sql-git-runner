<?php
// Example of an unsafe SQL query using user input without proper validation or sanitization

// Simulating a user input (e.g., from a GET request)
$user_id = $_GET['id'];

// Constructing a SQL query without using prepared statements
$sql = "SELECT * FROM users WHERE id = $user_id";

// Assuming $conn is your database connection
$conn = mysqli_connect("localhost", "username", "password", "database");

// Execute the unsafe query
$result = mysqli_query($conn, $sql);

// Fetch and display results
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "User ID: " . $row['id'] . "<br>";
        echo "Username: " . $row['username'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>

