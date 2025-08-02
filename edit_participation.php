<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}
$pid = $_GET['pid'] ?? null;
if (!$pid) {
    header("Location: view_participation.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $conn->prepare("DELETE FROM participation WHERE participation_id = :pid")
         ->execute([':pid' => $pid]);
    header("Location: view_participation.php?msg=deleted");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM participation WHERE participation_id = :pid");
$stmt->execute([':pid' => $pid]);
$part = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$part) { echo "Not found"; exit; }

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update = $conn->prepare("UPDATE participation SET 
        student_id=:sid, event_id=:eid, registered_at=:regat
        WHERE participation_id=:pid");
    if ($update->execute([
        ':sid' => $_POST['student_id'],
        ':eid' => $_POST['event_id'],
        ':regat' => $_POST['registered_at'],
        ':pid' => $pid
    ])) {
        $message = "✅ Updated successfully.";
        $stmt->execute([':pid' => $pid]);
        $part = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "❌ Update failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Participation</title></head>
<body>
<h2>Edit Participation</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="post">
    Student ID: <input name="student_id" value="<?= htmlspecialchars($part['student_id']) ?>"><br>
    Event ID: <input name="event_id" value="<?= htmlspecialchars($part['event_id']) ?>"><br>
    Registered At: <input type="datetime-local" name="registered_at" value="<?= date('Y-m-d\TH:i', strtotime($part['registered_at'])) ?>"><br>
    <button type="submit">Save Changes</button>
    <a href="view_participation.php">Cancel</a>
</form>
</body>
</html>
