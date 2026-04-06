<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    exit();
}

// ✅ use POST instead of GET
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0) {
    $conn->query("UPDATE contact_messages SET is_read = 1 WHERE id = $id");
}

header('Content-Type: application/json');
echo json_encode(['ok' => true]);