<?php
session_start();
include "../config/db.php";

// =============================
// RESPONSE HEADER (JSON)
// =============================
header('Content-Type: application/json');

// =============================
// VALIDATE REQUEST
// =============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}

// =============================
// GET USER ID (IMPORTANT)
// =============================
$user_id = 0;

// ✅ If you have login system
if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
}

// ⚠️ Fallback (temporary only if no login)
if ($user_id === 0 && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
}

// ❌ If still no user_id → stop
if ($user_id === 0) {
    echo json_encode(['success' => false, 'error' => 'User not identified']);
    exit();
}

// =============================
// GET & SANITIZE INPUTS
// =============================
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Basic validation
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit();
}

// Escape inputs
$name = $conn->real_escape_string($name);
$email = $conn->real_escape_string($email);
$message = $conn->real_escape_string($message);

// =============================
// INSERT MESSAGE (FIXED)
// =============================
$query = "
INSERT INTO contact_messages (user_id, name, email, message, is_read, created_at)
VALUES ($user_id, '$name', '$email', '$message', 0, NOW())
";

if ($conn->query($query)) {
    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => $conn->error
    ]);
}