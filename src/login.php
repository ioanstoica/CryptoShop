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
$password = $_POST["password"];

// Check if the user exists
$sql = "SELECT password FROM users WHERE user='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
        // Login successful
        session_start();
        $_SESSION["username"] = $username;
        echo "Welcome back, $username";
    } else {
        // Wrong password
        echo "Wrong password";
    }
} else {
    // User does not exist
    echo "No such user";
}

// Close the database connection
$conn->close();
