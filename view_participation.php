<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch participation data with location & college name from events table
$query = "
    SELECT 
        p.participation_id, 
        s.name AS student_name, 
        s.id AS student_id,
        e.event_name, 
        e.event_type, 
        e.date, 
        e.location,
        e.college_name
    FROM participation p
    JOIN student_info s ON p.student_id = s.id
    JOIN events e ON p.event_id = e.event_id
    ORDER BY p.participation_id ASC
";
$stmt = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Participation List</title>
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
        <div class="topnavleft"><a href="admin_home.php">Admin Home</a></div>
        <div class="topnavright"><a href="logout.php">Logout</a></div>
    </div>
    <h2>Participation Records</h2>
    <table>
        <tr>
            <th>Student</th>
            <th>Event</th>
            <th>Type</th>
            <th>Date</th>
            <th>College</th>
            <th>Location</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= $row['student_name'] ?> (<?= $row['student_id'] ?>)</td>
            <td><?= $row['event_name'] ?></td>
            <td><?= $row['event_type'] ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['college_name'] ?></td>
            <td><?= $row['location'] ?></td>
            <td><a class="edit-link" href="edit_participation.php?pid=<?= $row['participation_id'] ?>">Edit</a></td>
            <td><a class="delete-link" href="edit_participation.php?pid=<?= $row['participation_id'] ?>&delete=1" onclick="return confirm('Delete this record?')">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
