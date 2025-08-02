<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <div class="topnav">
    <div class="topnavleft">
      <a href="index.php">Home</a>
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
