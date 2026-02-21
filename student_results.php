<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION['id']) && !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// ✅ FIX: use COALESCE so course shows correctly
$stmt = $conn->query("
    SELECT r.result_id,
           s.name AS student_name,
           COALESCE(s.course, s.department, '') AS course,
           s.branch,
           s.id AS roll_number,
           r.sports, r.location, r.date, r.position
    FROM results r
    JOIN student_info s ON r.student_id = s.id
    ORDER BY r.date DESC
");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Results</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
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
        <a href="student_results.php" class="active">Results</a>
      </div>
      <div class="topnavright">
        <?php if (!isset($_SESSION['username'])): ?>
          <a href="contact_user.php">Contact</a>
        <?php endif; ?>
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
            <th>Sports</th>
            <th>Location</th>
            <th>Date</th>
            <th>Position</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($results as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['student_name']) ?></td>
              <td><?= $row['course'] !== '' ? htmlspecialchars($row['course']) : '-' ?></td>
              <td><?= $row['branch'] !== '' ? htmlspecialchars($row['branch']) : '-' ?></td>
              <td><?= htmlspecialchars($row['roll_number']) ?></td>
              <td><?= htmlspecialchars($row['sports']) ?></td>
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
