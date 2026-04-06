
<?php
session_start();
include "../config/db.php";
 
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
// ✅ Default values to prevent undefined errors
$search = '';
$result = null;
$unread_count = 0;

// ✅ Get search input safely
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// ✅ Main query
$query = "
SELECT 
    registrations.id,
    users.id as user_id,
    users.name,
    users.course,
    activities.name AS activity,
    categories.name AS category,
    (SELECT COUNT(*) FROM contact_messages WHERE contact_messages.user_id = users.id) AS message_count
FROM registrations
JOIN users ON registrations.user_id = users.id
JOIN activities ON registrations.activity_id = activities.id
JOIN categories ON activities.category_id = categories.id
";

// ✅ Apply search filter
if (!empty($search)) {
    $query .= "
    WHERE users.name LIKE '%$search%' 
    OR users.course LIKE '%$search%' 
    OR activities.name LIKE '%$search%' 
    OR categories.name LIKE '%$search%'
    ";
}

// ✅ Execute query safely
$result = $conn->query($query);

if (!$result) {
    die("Query Error: " . $conn->error); // Debugging
}

// ✅ Unread messages count
$unread_query = "SELECT COUNT(*) as cnt FROM contact_messages WHERE is_read = 0";
$unread_result = $conn->query($unread_query);

if ($unread_result && $row = $unread_result->fetch_assoc()) {
    $unread_count = $row['cnt'];
}
$query = "
SELECT 
    contact_messages.*,
    users.name AS user_name
FROM contact_messages
LEFT JOIN users 
    ON contact_messages.user_id = users.id
ORDER BY contact_messages.created_at DESC
"

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants &mdash; Admin</title>
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
                <h1>Participants</h1>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:13px; color:var(--text-muted);">Welcome, Admin</span>
                <a href="logout.php" class="btn btn-outline" style="font-size:13px; padding:7px 14px;">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <div class="admin-page">
            <div class="page-header">
                <h1><i class="fa fa-users" style="color:var(--primary);"></i> All Participants</h1>
                <form method="GET" class="search-bar">
                    <input type="text" name="search" placeholder="Search name, course, activity..."
                           value="<?php echo htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    <?php if ($search): ?>
                        <a href="participants.php" class="btn btn-outline">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="card" style="padding:0;">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Activity</th>
                                <th style="text-align:center;">Messages</th>
                                <th style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div style="font-weight:600; color:var(--text);"><?= htmlspecialchars($row['name']) ?></div>
                            </td>
                            <td><span style="font-size:13px; color:var(--text-muted);"><?= htmlspecialchars($row['course']) ?></span></td>
                            <td>
                                <span style="background:var(--primary-light); color:var(--primary); font-size:12px; font-weight:600; padding:3px 10px; border-radius:99px;">
                                    <?= htmlspecialchars($row['category']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['activity']) ?></td>
                            <td style="text-align:center;">
                                <?php if ($row['message_count'] > 0): ?>
                                    <a href="messages.php?user_id=<?= $row['user_id'] ?>" class="action-btn action-message" title="View <?= $row['message_count'] ?> message(s)">
                                        <i class="fa fa-envelope"></i>
                                    </a>
                                    <span class="badge" style="margin-left:4px;"><?= $row['message_count'] ?></span>
                                <?php else: ?>
                                    <span style="color:#cbd5e1; font-size:13px;"><i class="fa fa-envelope"></i></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-icons" style="justify-content:center;">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="action-btn action-edit" title="Edit participant">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <a href="../actions/delete.php?id=<?= $row['id'] ?>"
                                       class="action-btn action-delete"
                                       title="Delete participant"
                                       onclick="return confirm('Delete this participant? This cannot be undone.')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="../assets/script.js"></script>

</body>
</html>
