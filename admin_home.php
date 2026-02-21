<?php
session_start();
include_once("connection.php");

// Redirect if not logged in as admin
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Always force GET (avoid resubmission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: admin_home.php");
    exit;
}

$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    // ensure session is started before this
    $current = basename($_SERVER['PHP_SELF']);
    ?>
    <div class="topnav">
        <div class="topnavleft">
            <a href="index.php" <?= $current === 'index.php' ? 'class="active"' : '' ?>>Home</a>
            <a href="about.php" <?= $current === 'about.php' ? 'class="active"' : '' ?>>About</a>
            <?php if (isset($_SESSION['username'])): // admin ?>
                <a href="admin_home.php" <?= $current === 'admin_home.php' ? 'class="active"' : '' ?>>Admin Home</a>
                <a href="view_results.php" <?= in_array($current, ['view_results.php', 'add_result.php', 'edit_result.php']) ? 'class="active"' : '' ?>>Results</a>
            <?php elseif (isset($_SESSION['id'])): // student ?>
                <a href="user_show.php" <?= $current === 'user_show.php' ? 'class="active"' : '' ?>>Dashboard</a>
                <a href="student_results.php" <?= $current === 'student_results.php' ? 'class="active"' : '' ?>>Results</a>
            <?php endif; ?>
        </div>
        <div class="topnavright">
            <?php if (!isset($_SESSION['username']) && !isset($_SESSION['id'])): ?>
                <a href="index.php">Login / Signup</a>
            <?php endif; ?>
            <a href="contact_user.php" <?= $current === 'contact_user.php' ? 'class="active"' : '' ?>>Contact</a>
            <?php if (isset($_SESSION['username']) || isset($_SESSION['id'])): ?>
                <a href="logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </div>


    <div class="container">
        <div class="topnav">
            <div class="topnavleft">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
            </div>
            <div class="topnavright">
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></p>

        <ul>
            <li><a href="view_students.php">📋 View Students</a></li>
            <li><a href="view_participation.php">🏅 View Participation</a></li>
            <li><a href="add_event.php">➕ Add Event</a></li>
            <li><a href="view_events.php">View Events</a></li>
            <li> <a href="view_results.php">Results</a></li>

        </ul>
    </div>
</body>

</html>