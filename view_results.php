<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

$msg = $_GET['msg'] ?? '';

// Use COALESCE so code works whether your students table uses `course` or `department`
$stmt = $conn->query("
    SELECT r.result_id,
           s.name AS student_name,
           COALESCE(s.course, s.department, '') AS course,
           COALESCE(s.branch, '') AS branch,
           s.id AS roll_number,
           r.sports, r.location, r.date, r.position
    FROM results r
    JOIN student_info s ON r.student_id = s.id
    ORDER BY r.date DESC
");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Results</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <style>
    table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #555; padding:8px; text-align:left; }
        th { background:#2d7ff9; color:#fff; }
        .edit-link { color:blue; text-decoration:none; font-weight:bold; }
        .delete-link { color:red; text-decoration:none; font-weight:bold; }
  </style>
</head>
<body>
<div class="container">
  <div class="topnav">
    <div class="topnavleft">
      <a href="admin_home.php">Admin Home</a>
      <a href="view_results.php" class="active">Results</a>
      <a href="add_result.php">Add Result</a>
    </div>
    <div class="topnavright"><a href="logout.php">Logout</a></div>
  </div>

  <h2>Results</h2>
  <?php if ($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

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
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!$results): ?>
        <tr><td colspan="10">No results yet.</td></tr>
      <?php else: ?>
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
            <td><a class="edit-link" href="edit_result.php?id=<?= $row['result_id'] ?>">Edit</a></td>
            <td><a class="delete-link" href="edit_result.php?id=<?= $row['result_id'] ?>&delete=1" onclick="return confirm('Delete this result?')">Delete</a></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
