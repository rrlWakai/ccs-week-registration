<?php
$host = "sql112.infinityfree.com";
$username = "if0_41584373";
$password = "ZKAsVOjQ9OwK";
$database = "ccs_week_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset (recommended)
$conn->set_charset("utf8mb4");
?>