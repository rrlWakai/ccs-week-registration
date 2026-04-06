<?php
include "../config/db.php";

$id = $_POST['id'];
$name = $_POST['name'];
$course = $_POST['course'];
$activity_id = $_POST['activity'];

// Get user_id first
$user = $conn->query("
    SELECT user_id FROM registrations WHERE id = $id
")->fetch_assoc();

$user_id = $user['user_id'];

// Update user info
$conn->query("
    UPDATE users 
    SET name='$name', course='$course'
    WHERE id = $user_id
");

// Update activity
$conn->query("
    UPDATE registrations 
    SET activity_id=$activity_id
    WHERE id = $id
");

header("Location: ../admin/participants.php");
?>