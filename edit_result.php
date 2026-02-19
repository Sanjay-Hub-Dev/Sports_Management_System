<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: view_results.php");
    exit;
}

if (isset($_GET['delete'])) {
    $del = $conn->prepare("DELETE FROM results WHERE result_id = :id");
    $del->execute([':id' => $id]);
    header("Location: view_results.php?msg=deleted");
    exit;
}

$stmt = $conn->prepare("
    SELECT r.*, s.name, COALESCE(s.course, s.department, '') AS course, COALESCE(s.branch, '') AS branch, s.id AS roll_number
    FROM results r
    JOIN student_info s ON r.student_id = s.id
    WHERE r.result_id = :id
");
$stmt->execute([':id' => $id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$result) exit("Result not found.");

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sports = $_POST['sports'] ?? '';
    $location = $_POST['location'] ?? '';
    $date = $_POST['date'] ?? '';
    $position = $_POST['position'] ?? '';

    $update = $conn->prepare("
        UPDATE results SET sports = :sports, location = :location, date = :date, position = :position
        WHERE result_id = :id
    ");
    $success = $update->execute([
        ':sports' => $sports,
        ':location' => $location,
        ':date' => $date,
        ':position' => $position,
        ':id' => $id,
    ]);
    $message = $success ? "Updated successfully." : "Update failed.";
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Result</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <div class="topnav">
    <div class="topnavleft">
      <a href="admin_home.php">Admin Home</a>
      <a href="view_results.php" class="active">View Results</a>
    </div>
    <div class="topnavright"><a href="logout.php">Logout</a></div>
  </div>

  <h2>Edit Result</h2>
  <?php if ($message): ?>
    <div class="alert <?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="grid" style="grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:16px;">
      <div><label>Name</label><input value="<?= htmlspecialchars($result['name']) ?>" disabled></div>
      <div><label>Roll Number</label><input value="<?= htmlspecialchars($result['roll_number']) ?>" disabled></div>
      <div><label>Course</label><input value="<?= htmlspecialchars($result['course']) ?>" disabled></div>
      <div><label>Department</label><input value="<?= htmlspecialchars($result['branch']) ?>" disabled></div>
      <div><label for="sports">Sports</label><input name="sports" id="sports" value="<?= htmlspecialchars($result['sports']) ?>"></div>
      <div><label for="location">Location</label><input name="location" id="location" value="<?= htmlspecialchars($result['location']) ?>"></div>
      <div><label for="date">Date</label><input type="date" name="date" id="date" value="<?= htmlspecialchars($result['date']) ?>"></div>
      <div><label for="position">Position</label>
        <select name="position" id="position">
          <option value="1st" <?= $result['position'] === '1st' ? 'selected' : '' ?>>1st</option>
          <option value="2nd" <?= $result['position'] === '2nd' ? 'selected' : '' ?>>2nd</option>
          <option value="3rd" <?= $result['position'] === '3rd' ? 'selected' : '' ?>>3rd</option>
        </select>
      </div>
    </div>

    <div style="margin-top:16px; display:flex; gap:12px; flex-wrap:wrap;">
      <button type="submit" class="button">Save Changes</button>
      <a href="view_results.php" class="button secondary">Back</a>
      <a href="edit_result.php?id=<?= urlencode($id) ?>&delete=1" onclick="return confirm('Delete this entry?')" class="button" style="background:#d9534f;">Delete</a>
    </div>
  </form>
</div>
</body>
</html>
