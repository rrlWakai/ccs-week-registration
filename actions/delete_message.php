<?php
session_start();
include "../config/db.php";

// 🔒 Protect: only admins can delete
if (!isset($_SESSION['admin'])) {
    header("Location: ../admin/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    $delete_query = "DELETE FROM contact_messages WHERE id = '$id'";
    
    if ($conn->query($delete_query) === TRUE) {
        $_SESSION['success'] = "Message deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting message.";
    }
}

header("Location: ../admin/messages.php");
exit();
?>
