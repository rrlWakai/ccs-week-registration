<?php
include "../config/db.php";

if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);

    $query = $conn->query("SELECT * FROM activities WHERE category_id = $category_id");

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
    } else {
        echo "<option>No activities available</option>";
    }
}