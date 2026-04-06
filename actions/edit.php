<?php
include "../config/db.php";

$id = $_GET['id'];

$data = $conn->query("
    SELECT users.name, users.course, registrations.activity_id
    FROM registrations
    JOIN users ON registrations.user_id = users.id
    WHERE registrations.id = $id
")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">

<div class="card">
    <h1>Edit Participant</h1>

    <form method="POST" action="../actions/update.php">

        <input type="hidden" name="id" value="<?= $id ?>">

        <input type="text" name="name" value="<?= $data['name'] ?>" required>

        <input type="text" name="course" value="<?= $data['course'] ?>" required>

        <select name="activity">
            <?php
            $activities = $conn->query("SELECT * FROM activities");
            while($act = $activities->fetch_assoc()):
            ?>
                <option value="<?= $act['id'] ?>"
                    <?= $act['id'] == $data['activity_id'] ? 'selected' : '' ?>>
                    <?= $act['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button class="btn">Update</button>

    </form>

</div>

</div>

</body>
</html>