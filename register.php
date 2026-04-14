<?php include "config/db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="container header-flex">
        <h2>CCS Week</h2>
        <a href="index.php" class="btn">Back</a>
    </div>
</div>

<!-- MAIN -->
<div class="container" style="margin-top: 20px;">

    <div class="card form">

        <h1><i class="fa fa-user-plus"></i> Register Participant</h1>

        <form id="form">

            <!-- NAME -->
            <div class="form-group" style="margin-top: 20px;">
                <label>Full Name</label>
                <div class="input-wrapper">
                    <i class="fa fa-user"></i>
                    <input type="text" name="name" placeholder="Enter full name" required>
                </div>
            </div>

            <!-- COURSE -->
            <div class="form-group">
                <label>Course</label>
                <div class="input-wrapper">
                    <i class="fa fa-graduation-cap"></i>
                    <input type="text" name="course" placeholder="Enter course" required>
                </div>
            </div>

            <!-- CATEGORY -->
            <div class="form-group">
                <label>Category</label>
                <select name="category" id="category" required>
                    <option value="">Select Category</option>
                    <?php
                    $categories = $conn->query("SELECT * FROM categories");

                    if ($categories && $categories->num_rows > 0):
                        while($cat = $categories->fetch_assoc()):
                    ?>
                    <option value="<?= $cat['id'] ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                    <?php endwhile; else: ?>
                        <option disabled>No categories available</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- ACTIVITY -->
            <div class="form-group">
                <label>Activity</label>
                <select name="activity" id="activity" required>
                    <option value="">Select Category First</option>
                </select>
            </div>

            <!-- SUBMIT -->
            <button type="submit" class="btn" id="submitBtn">
                <i class="fa fa-paper-plane"></i> Submit Registration
            </button>

        </form>

    </div>

</div>

<!-- MODAL -->
<div class="modal" id="modal" style="display: none;">
    <div class="modal-box">
        <h2><i class="fa fa-check-circle"></i> Success</h2>
        <p>Participant successfully registered.</p>
        <br>
        <button class="btn" onclick="closeModal()">Continue</button>
    </div>
</div>

<script src="../assets/script.js" defer></script>

</body>
</html>