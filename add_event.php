<?php
session_start();
include_once("connection.php");

// Only allow admin
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ename   = $_POST['event_name'];
    $college = $_POST['college_name'];
    $location= $_POST['location'];
    $etype   = $_POST['event_type'];
    $edate   = $_POST['event_date'];
    $first   = $_POST['first_prize'];
    $second  = $_POST['second_prize'];
    $third   = $_POST['third_prize'];

    $stmt = $conn->prepare("INSERT INTO events 
        (event_name, college_name, location, event_type, date, first_prize, second_prize, third_prize)
        VALUES (:ename, :college, :location, :etype, :edate, :first, :second, :third)");
    $stmt->bindValue(':ename', $ename);
    $stmt->bindValue(':college', $college);
    $stmt->bindValue(':location', $location);
    $stmt->bindValue(':etype', $etype);
    $stmt->bindValue(':edate', $edate);
    $stmt->bindValue(':first', $first);
    $stmt->bindValue(':second', $second);
    $stmt->bindValue(':third', $third);

    if ($stmt->execute()) {
        $message = "✅ Event added successfully.";
    } else {
        $message = "❌ Failed to add event.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Event</title>
    <link rel="stylesheet" href="style.css">
    <style>
        form label { display:block; margin-top:10px; }
        form input, form select { width:100%; padding:6px; margin-top:4px; }
        .msg { margin:10px 0; padding:8px; border-radius:4px; }
        .success { background:#d4edda; color:#155724; }
        .error { background:#f8d7da; color:#721c24; }
    </style>
</head>
<body>
<div class="container">
    <div class="topnav">
        <div class="topnavleft"><a href="admin_home.php">Admin Home</a></div>
        <div class="topnavright"><a href="logout.php">Logout</a></div>
    </div>

    <h2>Add New Event</h2>

    <?php if ($message): ?>
        <div class="msg <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Sports Name</label>
        <input type="text" name="event_name" required>

        <label>College Name</label>
        <input type="text" name="college_name" required>

        <label>Location</label>
        <input type="text" name="location" required>

        <label>Event Type</label>
        <select name="event_type" required>
            <option value="Indoor">Indoor</option>
            <option value="Outdoor">Outdoor</option>
        </select>

        <label>Date</label>
        <input type="date" name="event_date" required>

        <label>1st Prize</label>
        <input type="text" name="first_prize">

        <label>2nd Prize</label>
        <input type="text" name="second_prize">

        <label>3rd Prize</label>
        <input type="text" name="third_prize">

        <br><br>
        <input type="submit" value="Add Event">
    </form>
</div>
</body>
</html>
