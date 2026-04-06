<?php
include "../config/db.php";

// Get data safely
$name = $conn->real_escape_string($_POST['name']);
$course = $conn->real_escape_string($_POST['course']);
$activity_id = intval($_POST['activity']);
$category_id = intval($_POST['category']);

// ✅ 1. VALIDATE ACTIVITY BELONGS TO CATEGORY
$activity_check = $conn->query("
    SELECT * FROM activities 
    WHERE id = $activity_id AND category_id = $category_id
");

if ($activity_check->num_rows == 0) {
    echo "Invalid activity selection.";
    exit();
}

// ✅ 2. CHECK IF USER EXISTS
$user_check = $conn->query("
    SELECT * FROM users 
    WHERE name='$name' AND course='$course'
");

if ($user_check->num_rows > 0) {
    $user = $user_check->fetch_assoc();
    $user_id = $user['id'];

    // ✅ 3. CHECK DUPLICATE REGISTRATION
    $check_registration = $conn->query("
        SELECT * FROM registrations 
        WHERE user_id = $user_id AND activity_id = $activity_id
    ");

    if ($check_registration->num_rows > 0) {
        echo "You are already registered in this activity.";
        exit();
    }

} else {
    // ✅ 4. INSERT NEW USER
    $insert_user = $conn->query("
        INSERT INTO users (name, course) 
        VALUES ('$name', '$course')
    ");

    if (!$insert_user) {
        echo "Failed to create user.";
        exit();
    }

    $user_id = $conn->insert_id;
}

// ✅ 5. INSERT REGISTRATION
$insert_registration = $conn->query("
    INSERT INTO registrations (user_id, activity_id) 
    VALUES ($user_id, $activity_id)
");

if (!$insert_registration) {
    echo "Failed to register.";
    exit();
}

// ✅ SUCCESS RESPONSE (VERY IMPORTANT FOR MODAL)
echo "success";