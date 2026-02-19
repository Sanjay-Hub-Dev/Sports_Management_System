<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$student_id = $_SESSION['id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'] ?? '';

    if ($event_id) {
        // Check if already registered
        $check = $conn->prepare("SELECT 1 FROM participation WHERE student_id = :sid AND event_id = :eid");
        $check->execute([':sid' => $student_id, ':eid' => $event_id]);
        if ($check->fetch()) {
            $message = "⚠ You are already registered for this event!";
        } else {
            $insert = $conn->prepare("INSERT INTO participation (student_id, event_id) VALUES (:sid, :eid)");
            if ($insert->execute([':sid' => $student_id, ':eid' => $event_id])) {
                $message = "✅ Registered successfully.";
            } else {
                $message = "❌ Registration failed.";
            }
        }
    }
}

$events = $conn->query("SELECT * FROM events ORDER BY date ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Register Event</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <div class="topnav">
    <div class="topnavleft"><a href="user_show.php">Dashboard</a><a href="register_event.php" class="active">Register Event</a></div>
    <div class="topnavright"><a href="logout.php">Logout</a></div>
  </div>

  <h2>Register for Event</h2>
  <?php if ($message): ?><div class="alert"><?= htmlspecialchars($message) ?></div><?php endif; ?>

  <form method="post">
    <label for="event_id">Select Event</label>
    <select name="event_id" id="event_id" required>
      <option value="">-- Select Event --</option>
      <?php while ($e = $events->fetch(PDO::FETCH_ASSOC)): ?>
        <option value="<?= $e['event_id'] ?>"><?= htmlspecialchars($e['event_name']) ?> (<?= htmlspecialchars($e['date']) ?>)</option>
      <?php endwhile; ?>
    </select>
    <br><br>
    <button type="submit" class="button">Register</button>
  </form>
</div>
</body>
</html>
