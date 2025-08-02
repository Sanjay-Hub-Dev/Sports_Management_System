<?php
session_start();
include_once("connection.php");

// Require at least student or admin login
if (!isset($_SESSION['id']) && !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Fetch all results
$stmt = $conn->query("SELECT * FROM results ORDER BY date DESC");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Results</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <style>
    table { width:100%; border-collapse: collapse; margin-top:16px; }
    th, td { border:1px solid #555; padding:8px; text-align:left; }
    th { background:#2d7ff9; color:#fff; }
    tr:nth-child(odd){ background:#f5f8fc; }
    .topnav { display:flex; justify-content:space-between; background:burlywood; padding:8px; border-radius:6px; }
    .topnav a { color:cornsilk; text-decoration:none; padding:8px 12px; font-size:16px; }
    .topnav a:hover { background:rgba(0,0,0,0.1); border-radius:4px; }
    .container { max-width:1000px; margin:40px auto; font-family: system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif; }
  </style>
</head>
<body>
  <div class="container">
    <div class="topnav">
      <div class="topnavleft">
        <?php if (isset($_SESSION['username'])): ?>
          <a href="admin_home.php">Admin Home</a>
        <?php else: ?>
          <a href="user_show.php">Dashboard</a>
        <?php endif; ?>
      </div>
      <div class="topnavright">
        <?php if (!isset($_SESSION['username'])): ?>
          <a href="contact_user.php">Contact</a>
        <?php endif; ?>
        <a href="student_results.php">Results</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <h2>All Results</h2>

    <?php if (empty($results)): ?>
      <p>No results available yet.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Course</th>
            <th>Department</th>
            <th>Roll Number</th>
            <th>Location</th>
            <th>Date</th>
            <th>Position</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($results as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['student_name']) ?></td>
              <td><?= htmlspecialchars($row['course']) ?></td>
              <td><?= htmlspecialchars($row['department']) ?></td>
              <td><?= htmlspecialchars($row['roll_number']) ?></td>
              <td><?= htmlspecialchars($row['location']) ?></td>
              <td><?= htmlspecialchars($row['date']) ?></td>
              <td><?= htmlspecialchars($row['position']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
