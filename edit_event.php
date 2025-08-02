<?php
session_start();
include_once("connection.php");

// Only allow admin
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

$event_id = $_GET['event_id'] ?? null;
if (!$event_id) {
    header("Location: view_events.php");
    exit;
}

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE event_id = :eid");
$stmt->bindValue(':eid', $event_id, PDO::PARAM_INT);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit;
}

$message = "";

// Handle delete action
if (isset($_POST['delete'])) {
    $del = $conn->prepare("DELETE FROM events WHERE event_id = :eid");
    $del->bindValue(':eid', $event_id, PDO::PARAM_INT);
    if ($del->execute()) {
        header("Location: view_events.php?msg=deleted");
        exit;
    } else {
        $message = "❌ Failed to delete event.";
    }
}

// Handle update action
if (isset($_POST['update'])) {
    $ename   = $_POST['event_name'];
    $college = $_POST['college_name'];
    $location= $_POST['location'];
    $etype   = $_POST['event_type'];
    $edate   = $_POST['event_date'];
    $first   = $_POST['first_prize'];
    $second  = $_POST['second_prize'];
    $third   = $_POST['third_prize'];
    $desc    = $_POST['description'];

    $update = $conn->prepare("UPDATE events 
        SET event_name = :ename,
            college_name = :college,
            location = :location,
            event_type = :etype,
            date = :edate,
            first_prize = :first,
            second_prize = :second,
            third_prize = :third,
            description = :desc
        WHERE event_id = :eid");

    $update->bindValue(':ename', $ename);
    $update->bindValue(':college', $college);
    $update->bindValue(':location', $location);
    $update->bindValue(':etype', $etype);
    $update->bindValue(':edate', $edate);
    $update->bindValue(':first', $first);
    $update->bindValue(':second', $second);
    $update->bindValue(':third', $third);
    $update->bindValue(':desc', $desc);
    $update->bindValue(':eid', $event_id);

    if ($update->execute()) {
        $message = "✅ Event updated successfully.";
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "❌ Failed to update event.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="style.css">
    <style>
        form label { display:block; margin-top:10px; }
        form input, form select, form textarea { width:100%; padding:6px; margin-top:4px; }
        .msg { margin:10px 0; padding:8px; border-radius:4px; }
        .success { background:#d4edda; color:#155724; }
        .error { background:#f8d7da; color:#721c24; }
        .danger { background:#ffdddd; color:#a94442; }
        .btn-delete { background:#d9534f; color:white; border:none; padding:8px 14px; cursor:pointer; }
        .btn-update { background:#0275d8; color:white; border:none; padding:8px 14px; cursor:pointer; }
        .btn-cancel { padding:8px 14px; text-decoration:none; background:#6c757d; color:white; }
    </style>
</head>
<body>
<div class="container">
    <div class="topnav">
        <div class="topnavleft"><a href="admin_home.php">Admin Home</a></div>
        <div class="topnavright"><a href="logout.php">Logout</a></div>
    </div>

    <h2>Edit Event</h2>

    <?php if ($message): ?>
        <div class="msg <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Sports Name</label>
        <input type="text" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required>

        <label>College Name</label>
        <input type="text" name="college_name" value="<?= htmlspecialchars($event['college_name']) ?>" required>

        <label>Location</label>
        <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>

        <label>Event Type</label>
        <select name="event_type" required>
            <option value="Indoor" <?= $event['event_type'] === 'Indoor' ? 'selected' : '' ?>>Indoor</option>
            <option value="Outdoor" <?= $event['event_type'] === 'Outdoor' ? 'selected' : '' ?>>Outdoor</option>
        </select>

        <label>Date</label>
        <input type="date" name="event_date" value="<?= htmlspecialchars($event['date']) ?>" required>

        <label>1st Prize</label>
        <input type="text" name="first_prize" value="<?= htmlspecialchars($event['first_prize']) ?>">

        <label>2nd Prize</label>
        <input type="text" name="second_prize" value="<?= htmlspecialchars($event['second_prize']) ?>">

        <label>3rd Prize</label>
        <input type="text" name="third_prize" value="<?= htmlspecialchars($event['third_prize']) ?>">

        <label>Description</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($event['description']) ?></textarea>

        <br><br>
        <input type="submit" name="update" class="btn-update" value="Save Changes">
        <button type="submit" name="delete" class="btn-delete" onclick="return confirm('Are you sure you want to delete this event?')">Delete Event</button>
        <a href="view_events.php" class="btn-cancel">Cancel</a>
    </form>
</div>
</body>
</html>
