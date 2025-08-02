<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}
$stmt = $conn->query("SELECT * FROM student_info ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
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
    <h2>Students List</h2>
    <table>
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Gender</th>
            <th>DOB</th>
            <th>Course</th> <!-- was Department -->
            <th>Branch</th> <!-- new -->
            <th>Year</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['DOB']) ?></td>
            <td><?= htmlspecialchars($row['department']) ?></td> <!-- displayed as Course -->
            <td><?= htmlspecialchars($row['branch'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['year']) ?></td>
            <td><a class="edit-link" href="edit_student.php?id=<?= urlencode($row['id']) ?>">Edit</a></td>
            <td><a class="delete-link" href="edit_student.php?id=<?= urlencode($row['id']) ?>&delete=1" onclick="return confirm('Delete this student?')">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
