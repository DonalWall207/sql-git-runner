<?php
$conn = new PDO('mysql:host=localhost;dbname=test', $user, $pass);
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute();
?>

