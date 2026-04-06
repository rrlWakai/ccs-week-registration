<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);

$data = $conn->query("
    SELECT users.name, users.course, registrations.activity_id
    FROM registrations
    JOIN users ON registrations.user_id = users.id
    WHERE registrations.id = $id
")->fetch_assoc();

if (!$data) {
    header("Location: participants.php");
    exit();
}

// Unread count for badge
$unread_result = $conn->query("SELECT COUNT(*) as cnt FROM contact_messages WHERE COALESCE(is_read, 0) = 0");
$unread_count = $unread_result ? $unread_result->fetch_assoc()['cnt'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Participant &mdash; Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>

<div class="admin-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="fa fa-code"></i></div>
            <div class="sidebar-brand-text">
                CCS Week
                <span>Admin Panel</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu</div>
            <a href="participants.php" class="nav-item active">
                <span class="nav-icon"><i class="fa fa-users"></i></span>
                Participants
            </a>
            <a href="messages.php" class="nav-item">
                <span class="nav-icon"><i class="fa fa-inbox"></i></span>
                Inbox
                <?php if ($unread_count > 0): ?>
                    <span class="nav-badge"><?= $unread_count ?></span>
                <?php endif; ?>
            </a>
            <a href="messages.php?filter=favorites" class="nav-item">
                <span class="nav-icon"><i class="fa fa-star"></i></span>
                Favorites
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="nav-item">
                <span class="nav-icon"><i class="fa fa-sign-out-alt"></i></span>
                Logout
            </a>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- MAIN CONTENT -->
    <main class="admin-content">
        <div class="admin-topbar">
            <div style="display:flex; align-items:center; gap:14px;">
                <button class="hamburger" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
                <h1>Edit Participant</h1>
            </div>
            <a href="participants.php" class="btn btn-outline" style="font-size:13px; padding:7px 14px;">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="admin-page">
            <div style="max-width:600px; margin:0 auto;">
                <div class="card">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:28px; padding-bottom:20px; border-bottom:1px solid var(--border);">
                        <div style="width:44px; height:44px; background:var(--primary-light); border-radius:var(--radius); display:flex; align-items:center; justify-content:center; font-size:18px; color:var(--primary);">
                            <i class="fa fa-pencil-alt"></i>
                        </div>
                        <div>
                            <h2 style="margin:0; text-align:left; font-size:18px;">Edit Participant</h2>
                            <p style="margin:0; font-size:13px;">Update name, course, and activity</p>
                        </div>
                    </div>

                    <form method="POST" action="../actions/update.php">
                        <input type="hidden" name="id" value="<?= $id ?>">

                        <div class="form-group">
                            <label for="name"><i class="fa fa-user" style="color:var(--primary); margin-right:6px;"></i>Full Name</label>
                            <input type="text" id="name" name="name"
                                   value="<?= htmlspecialchars($data['name']) ?>" required
                                   placeholder="Participant name">
                        </div>

                        <div class="form-group">
                            <label for="course"><i class="fa fa-graduation-cap" style="color:var(--primary); margin-right:6px;"></i>Course</label>
                            <input type="text" id="course" name="course"
                                   value="<?= htmlspecialchars($data['course']) ?>" required
                                   placeholder="e.g. BSIT, BSCS">
                        </div>

                        <div class="form-group">
                            <label for="activity"><i class="fa fa-trophy" style="color:var(--primary); margin-right:6px;"></i>Activity</label>
                            <select id="activity" name="activity">
                                <?php
                                $activities = $conn->query("SELECT activities.*, categories.name AS cat_name FROM activities JOIN categories ON activities.category_id = categories.id ORDER BY categories.name, activities.name");
                                $current_cat = '';
                                while ($act = $activities->fetch_assoc()):
                                    if ($act['cat_name'] !== $current_cat) {
                                        if ($current_cat !== '') echo '</optgroup>';
                                        echo '<optgroup label="' . htmlspecialchars($act['cat_name']) . '">';
                                        $current_cat = $act['cat_name'];
                                    }
                                ?>
                                    <option value="<?= $act['id'] ?>" <?= $act['id'] == $data['activity_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($act['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                                <?php if ($current_cat) echo '</optgroup>'; ?>
                            </select>
                        </div>

                        <div style="display:flex; gap:12px; margin-top:8px;">
                            <button type="submit" class="btn btn-primary" style="flex:1;">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                            <a href="participants.php" class="btn btn-outline" style="flex:1; text-align:center;">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('active');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
}
</script>
</body>
</html>
