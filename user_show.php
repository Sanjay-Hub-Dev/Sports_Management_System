<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="topnav">
      <div class="topnavleft">
        <a class="active" href="user_show.php">Home</a>
      </div>
      <div class="topnavright">
        <a href="contact_user.php">Contact</a>
        <a href="student_results.php">Results</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h2>
    <p>Your ID: <?= htmlspecialchars($_SESSION['id']) ?></p>

    <ul>
      <li><a href="participation.php">Register for Events</a></li>
      <li><a href="view_events.php">View Events</a></li>
    </ul>
  </div>
</body>
</html>
