<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION['username']) && !isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->query("SELECT * FROM events ORDER BY event_id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Events</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #555; padding: 8px; text-align: left; }
        th { background: #2d7ff9; color: #fff; }
        tr:nth-child(odd) { background: #f5f8fc; }
        .edit-link { text-decoration: none; color: #2d7ff9; font-weight: bold; }
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
            <div class="topnavright"><a href="logout.php">Logout</a></div>
        </div>

        <h2>Events List</h2>

        <table>
            <tr>
                <th>Sports Name</th>
                <th>College Name</th>
                <th>Location</th>
                <th>Event Type</th>
                <th>Date</th>
                <th>1st Prize</th>
                <th>2nd Prize</th>
                <th>3rd Prize</th>
                <?php if (isset($_SESSION['username'])): ?><th>Edit</th><?php endif; ?>
            </tr>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['event_name']) ?></td>
                    <td><?= htmlspecialchars($row['college_name']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['event_type']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['first_prize']) ?></td>
                    <td><?= htmlspecialchars($row['second_prize']) ?></td>
                    <td><?= htmlspecialchars($row['third_prize']) ?></td>
                    <?php if (isset($_SESSION['username'])): ?>
                        <td><a class="edit-link" href="edit_event.php?event_id=<?= $row['event_id'] ?>">Edit</a></td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
