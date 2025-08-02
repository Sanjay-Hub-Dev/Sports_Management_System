<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Handle registration
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = $_SESSION['id'];
    $event_id = $_POST['event_id'];
    $player_role = $_POST['player_role'];

    $stmt = $conn->prepare("INSERT INTO participation (student_id, event_id, player_role) VALUES (:s, :e, :r)");
    $stmt->bindValue(':s', $student);
    $stmt->bindValue(':e', $event_id);
    $stmt->bindValue(':r', $player_role);
    if ($stmt->execute()) {
        $message = "Registered for event successfully.";
    } else {
        $message = "Registration failed.";
    }
}

// Fetch available events
$events = $conn->query("SELECT * FROM events ORDER BY date ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Participate in Events</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="topnav">
      <div class="topnavleft">
        <a href="user_show.php">Dashboard</a>
      </div>
      <div class="topnavright">
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <h2>Register for an Event</h2>
    <?php if ($message): ?><div class="alert success"><?= htmlspecialchars($message) ?></div><?php endif; ?>

    <form method="post">
      <label for="event_id">Choose Event:</label><br>
      <select name="event_id" id="event_id" required>
        <?php while ($e = $events->fetch(PDO::FETCH_ASSOC)): ?>
          <option value="<?= $e['event_id'] ?>">
            <?= htmlspecialchars($e['event_name']) ?> (<?= htmlspecialchars($e['event_type']) ?>) on <?= htmlspecialchars($e['date']) ?>
          </option>
        <?php endwhile; ?>
      </select><br><br>

      <input type="submit" class="button" value="Register">
    </form>

    <p><a href="user_show.php">Back to Dashboard</a></p>
  </div>
</body>
</html>
