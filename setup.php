<?php
include "config/db.php";

$errors   = [];
$messages = [];

// ── 1. Create contact_messages table with all required columns ────────────────
$sql_create = "CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id`          INT PRIMARY KEY AUTO_INCREMENT,
  `name`        VARCHAR(100) NOT NULL,
  `phone`       VARCHAR(20)  NOT NULL,
  `email`       VARCHAR(100) NOT NULL,
  `message`     TEXT         NOT NULL,
  `user_id`     INT,
  `is_read`     TINYINT(1)   DEFAULT 0,
  `is_favorite` TINYINT(1)   DEFAULT 0,
  `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX(`user_id`),
  INDEX(`created_at`)
)";

if ($conn->query($sql_create) === TRUE) {
    $messages[] = "contact_messages table is ready.";
} else {
    $errors[] = "Create table error: " . $conn->error;
}

// ── 2. Add is_read column if it does not exist (for existing installations) ───
$sql_add_read = "ALTER TABLE `contact_messages` ADD COLUMN IF NOT EXISTS `is_read` TINYINT(1) DEFAULT 0";
if ($conn->query($sql_add_read) === TRUE) {
    $messages[] = "is_read column verified.";
} else {
    $errors[] = "ALTER TABLE (is_read) error: " . $conn->error;
}

// ── 3. Add is_favorite column if it does not exist ────────────────────────────
$sql_add_fav = "ALTER TABLE `contact_messages` ADD COLUMN IF NOT EXISTS `is_favorite` TINYINT(1) DEFAULT 0";
if ($conn->query($sql_add_fav) === TRUE) {
    $messages[] = "is_favorite column verified.";
} else {
    $errors[] = "ALTER TABLE (is_favorite) error: " . $conn->error;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup</title>
    <style>
        body { font-family: sans-serif; padding: 40px; background: #f8fafc; }
        .box { max-width: 600px; margin: 0 auto; padding: 28px 32px; border-radius: 10px; }
        .success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        h2 { margin-top: 0; }
        ul { margin: 12px 0 20px; padding-left: 20px; }
        li { margin-bottom: 6px; font-size: 14px; }
        a button { background: #4f46e5; color: white; padding: 10px 22px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; }
        a button:hover { background: #3730a3; }
    </style>
</head>
<body>
<?php if (empty($errors)): ?>
    <div class="box success">
        <h2>&#10003; Setup Complete</h2>
        <ul>
            <?php foreach ($messages as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="public/index.php"><button>Go to Home</button></a>
    </div>
<?php else: ?>
    <div class="box error">
        <h2>&#10007; Setup Errors</h2>
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php if (!empty($messages)): ?>
            <p><strong>Completed steps:</strong></p>
            <ul>
                <?php foreach ($messages as $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>
</body>
</html>
