<?php
session_start();
include "../config/db.php";

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $result = $conn->query("SELECT * FROM admins WHERE username='$username' AND password='$password'");

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        header("Location: participants.php");
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div style="display:grid; grid-template-columns: 1fr 1fr; height:100vh;">

    <!-- LEFT SIDE (BRANDING) -->
    <div style="background:#0f172a; color:white; display:flex; align-items:center; justify-content:center;">
        <div style="max-width:300px;">
          
        <img src="../assets/images/logo.jpg" alt="logo" style="width:100%; border-radius:10px;">
            <p style="color:#94a3b8; margin-top:10px;">
                Admin panel for managing participants and registrations.
            </p>
             
        </div>
    </div>

    <!-- RIGHT SIDE (FORM) -->
    <div style="display:flex; align-items:center; justify-content:center;">

        <div style="width:350px;">

            <h2>Admin Login</h2>
            <p style="margin-bottom:20px;">Enter your credentials</p>

            <?php if($error): ?>
                <div style="background:#fee2e2; color:#991b1b; padding:10px; margin-bottom:15px;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <input type="text" name="username" placeholder="Username" required>

                <input type="password" name="password" placeholder="Password" required style=" margin-top: 20px">

                <button style="margin-top: 20px;" name="login" class="btn">Login</button>

            </form>

        </div>

    </div>

</div>

</body>
</html>