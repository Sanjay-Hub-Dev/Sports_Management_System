<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

$stmt = $conn->query("SELECT * FROM results ORDER BY result_id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Results</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #555; padding:8px; text-align:left; }
        th { background:#2d7ff9; color:#fff; }
        .edit-link { color:blue; font-weight:bold; text-decoration:none; }
        .delete-link { color:red; font-weight:bold; text-decoration:none; }
    </style>
</head>
<body>
<div class="container">
    <div class="topnav">
        <div class="topnavleft"><a href="admin_home.php">Admin Home</a></div>
        <div class="topnavright"><a href="logout.php">Logout</a></div>
    </div>

    <h2>Results</h2>
    <a href="add_result.php">Add Result</a>
    <table>
        <tr>
            <th>Name</th>
            <th>Course</th>
            <th>Department</th>
            <th>Roll Number</th>
            <th>Location</th>
            <th>Date</th>
            <th>Position</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= htmlspecialchars($row['course']) ?></td>
            <td><?= htmlspecialchars($row['department']) ?></td>
            <td><?= htmlspecialchars($row['roll_number']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= htmlspecialchars($row['position']) ?></td>
            <td><a class="edit-link" href="edit_result.php?id=<?= $row['result_id'] ?>">Edit</a></td>
            <td><a class="delete-link" href="edit_result.php?id=<?= $row['result_id'] ?>&delete=1" onclick="return confirm('Delete this result?')">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
