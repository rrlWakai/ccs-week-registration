<?php
include "../config/db.php";

$id = $_GET['id'];

// Delete registration only
$conn->query("DELETE FROM registrations WHERE id = $id");

header("Location: ../admin/participants.php");
?>