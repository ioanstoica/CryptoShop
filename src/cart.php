<?php

require_once "db_connect.php";
$conn = db_connect();

// Check if table "cart" already exists
$check_table = "SELECT * FROM information_schema.tables WHERE table_name = 'cart'";
$table_exists = $conn->query($check_table);

if (!$table_exists) {
    // Create table "cart"
    $create_table = "CREATE TABLE cart (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user VARCHAR(255) NOT NULL,
        coin VARCHAR(255) NOT NULL,
        amount INT(11) NOT NULL
    )";

    if ($conn->query($create_table) === TRUE) {
        echo "Table cart created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
} else {
    echo "Table cart already exists";
}

$conn->close();
