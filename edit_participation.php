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

// Delete flow
if (isset($_GET['delete'])) {
    $conn->prepare("DELETE FROM participation WHERE participation_id = :pid")
         ->execute([':pid' => $pid]);
    header("Location: view_participation.php?msg=deleted");
    exit;
}

// Fetch participation with student + event info
$stmt = $conn->prepare("
    SELECT p.*, s.name AS student_name, s.course, s.branch, s.id AS roll_number, 
           e.event_name, e.location, e.date
    FROM participation p
    JOIN student_info s ON p.student_id = s.id
    JOIN events e ON p.event_id = e.event_id
    WHERE p.participation_id = :pid
");
$stmt->execute([':pid' => $pid]);
$part = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$part) { exit("Participation not found."); }

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update = $conn->prepare("UPDATE participation 
        SET student_id = :sid, event_id = :eid, player_role = :role
        WHERE participation_id = :pid");
    $success = $update->execute([
        ':sid'  => $_POST['student_id'],
        ':eid'  => $_POST['event_id'],
        ':role' => $_POST['player_role'],
        ':pid'  => $pid
    ]);
    $message = $success ? "✅ Updated successfully." : "❌ Update failed.";

    $stmt->execute([':pid' => $pid]);
    $part = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch students + events for dropdowns
$students = $conn->query("SELECT id, name, course, branch FROM student_info ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$events   = $conn->query("SELECT event_id, event_name, location, date FROM events ORDER BY date ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Participation</title>
  <link rel="stylesheet" href="style.css">
  <style>
    label { display:block; margin:8px 0 4px; font-weight:600; }
    input, select { width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px; box-sizing:border-box; }
    .btn { padding:10px 16px; border:none; border-radius:6px; cursor:pointer; }
    .primary { background:#2d7ff9; color:#fff; }
    .danger { background:#d9534f; color:#fff; }
    .message { padding:10px; border-radius:6px; margin-bottom:12px; }
    .success { background:#d4edda; color:#155724; }
    .error { background:#f8d7da; color:#721c24; }
    .flex { display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
  </style>
</head>
<body>
<div class="container">
  <div class="topnav">
    <div class="topnavleft"><a href="admin_home.php">Admin Home</a></div>
    <div class="topnavright"><a href="logout.php">Logout</a></div>
  </div>

  <h2>Edit Participation</h2>
  <?php if ($message): ?>
    <div class="message <?= strpos($message, 'Updated') !== false ? 'success' : 'error' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="flex">
      <div>
        <label for="student_id">Student</label>
        <select name="student_id" id="student_id" required>
          <?php foreach ($students as $s): ?>
            <option value="<?= $s['id'] ?>" <?= $s['id'] == $part['student_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['course']) ?> - <?= htmlspecialchars($s['branch']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label for="event_id">Event</label>
        <select name="event_id" id="event_id" required>
          <?php foreach ($events as $e): ?>
            <option value="<?= $e['event_id'] ?>" <?= $e['event_id'] == $part['event_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($e['event_name']) ?> - <?= htmlspecialchars($e['location']) ?> (<?= $e['date'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label for="player_role">Player Role</label>
        <input name="player_role" id="player_role" value="<?= htmlspecialchars($part['player_role']) ?>">
      </div>
    </div>

    <div style="margin-top:16px;">
      <button type="submit" class="btn primary">Save Changes</button>
      <a href="view_participation.php" class="btn" style="background:#6c757d; color:#fff; text-decoration:none;">Cancel</a>
      <a href="edit_participation.php?pid=<?= urlencode($pid) ?>&delete=1" onclick="return confirm('Are you sure you want to delete this participation?')" class="btn danger">Delete</a>
    </div>
  </form>
</div>
</body>
</html>
