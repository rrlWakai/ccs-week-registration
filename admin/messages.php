<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// =============================
// INPUTS
// =============================
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$user_id_filter = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// =============================
// WHERE CONDITIONS
// =============================
$where = array();

if ($filter === 'favorites') {
    $where[] = "COALESCE(contact_messages.is_favorite, 0) = 1";
}

if ($user_id_filter > 0) {
    $where[] = "contact_messages.user_id = $user_id_filter";
}

if (!empty($search)) {
    $where[] = "(
        contact_messages.name LIKE '%$search%' OR 
        contact_messages.email LIKE '%$search%' OR 
        contact_messages.message LIKE '%$search%'
    )";
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// =============================
// QUERY
// =============================
$query = "
SELECT
    contact_messages.*,
    users.name AS participant_name
FROM contact_messages
LEFT JOIN users 
    ON contact_messages.user_id = users.id
$where_sql
ORDER BY contact_messages.created_at DESC
";

$result = $conn->query($query);

if (!$result) {
    die("Query Error: " . $conn->error);
}

// =============================
// UNREAD COUNT (FIXED)
// =============================
$unread_result = $conn->query("SELECT COUNT(*) as cnt FROM contact_messages WHERE is_read = 0");
$unread_count = $unread_result ? $unread_result->fetch_assoc()['cnt'] : 0;

// =============================
// DATA
// =============================
$messages = array();
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inbox</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="admin-wrapper">

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon"><i class="fa fa-code"></i></div>
        <div class="sidebar-brand-text">
            CCS Week
            <span>Admin Panel</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu</div>

        <a href="participants.php" class="nav-item">
            <span class="nav-icon"><i class="fa fa-users"></i></span>
            Participants
        </a>

        <a href="messages.php" class="nav-item active">
            <span class="nav-icon"><i class="fa fa-inbox"></i></span>
            Inbox 
            <?php if ($unread_count > 0): ?>
                <span class="badge"><?php echo $unread_count; ?></span>
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

<!-- MAIN -->
<main class="admin-content">

    <div class="admin-topbar">
        <h1>Inbox</h1>
    </div>

    <div class="inbox-layout">

        <!-- LEFT -->
        <div class="inbox-list">

            <form method="GET" class="search-bar" style="padding:15px;">
                <input type="text" name="search" placeholder="Search..."
                    value="<?php echo htmlspecialchars($search); ?>">
            </form>

            <div class="msg-list">

            <?php if (count($messages) > 0): ?>
                <?php foreach ($messages as $msg): ?>

                <?php
                    $name = $msg['participant_name'] ? $msg['participant_name'] : $msg['name'];
                    $preview = substr($msg['message'], 0, 50);
                    $is_unread = !$msg['is_read'];
                ?>

                <div class="msg-row <?php echo $is_unread ? 'unread' : ''; ?>"
                     onclick="openMessage(<?php echo $msg['id']; ?>, this)">

                    <div class="msg-avatar">
                        <?php echo strtoupper(substr($name, 0, 1)); ?>
                    </div>

                    <div class="msg-body">
                        <div class="msg-sender"><?php echo htmlspecialchars($name); ?></div>
                        <div class="msg-preview"><?php echo htmlspecialchars($preview); ?></div>
                    </div>

                    <div class="msg-date">
                        <?php echo date("M d", strtotime($msg['created_at'])); ?>
                    </div>

                </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="msg-empty">
                    <i class="fa fa-inbox"></i>
                    <p>No messages found.</p>
                </div>
            <?php endif; ?>

            </div>
        </div>

        <!-- RIGHT -->
        <div class="inbox-view" id="messageView">
            <div class="empty-view">
                <i class="fa fa-inbox"></i>
                <p>Select a message</p>
            </div>
        </div>

    </div>

</main>
</div>

<script>
const messages = <?php echo json_encode($messages); ?>;

function openMessage(id, el) {
    var msg = messages.find(m => m.id == id);
    if (!msg) return;

    // ✅ mark as read in DB
    fetch('/actions/mark_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + id
    });

    // ✅ render message
    document.getElementById("messageView").innerHTML = `
        <div class="card">
            <h2>${msg.name}</h2>
            <p style="color:gray;">
                ${msg.email} | ${msg.phone || ''}
            </p>
            <hr style="margin:15px 0;">
            <p>${msg.message}</p>
        </div>
    `;

    // ✅ UI updates
    document.querySelectorAll('.msg-row').forEach(row => {
        row.classList.remove('active');
    });

    el.classList.add('active');
    el.classList.remove('unread');

    // ✅ update badge count instantly
    let badge = document.querySelector('.badge');
    if (badge) {
        let count = parseInt(badge.innerText);
        if (count > 0) {
            count--;
            if (count <= 0) {
                badge.remove();
            } else {
                badge.innerText = count;
            }
        }
    }
}
</script>

</body>
</html>