<?php

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "87654321";
$dbname = "daw";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

// Hash the password
$password = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user into the users table
$sql = "INSERT INTO users (user, email, password) VALUES ('$username', '$email', '$password')";
if ($conn->query($sql) === TRUE) {
    echo "Signup successful! Welcome $username";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
