<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? '';
    $sports     = trim($_POST['sports'] ?? '');
    $location   = trim($_POST['location'] ?? '');
    $date       = $_POST['date'] ?? null;
    $position   = $_POST['position'] ?? '';

    if (empty($student_id) || empty($sports) || empty($position) || empty($date)) {
        $message = "Please fill required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO results (student_id, sports, location, date, position) 
                                VALUES (:student_id, :sports, :location, :date, :position)");
        $stmt->bindValue(':student_id', $student_id);
        $stmt->bindValue(':sports', $sports);
        $stmt->bindValue(':location', $location);
        $stmt->bindValue(':date', $date);
        $stmt->bindValue(':position', $position);
        if ($stmt->execute()) {
            header("Location: view_results.php?msg=" . urlencode("Result added successfully."));
            exit;
        } else {
            $message = "Failed to add result.";
        }
    }
}

// Fetch students and show course/branch if available. COALESCE handles older column names.
$students = $conn->query("SELECT id, name, COALESCE(course, department, '') AS course, COALESCE(branch, '') AS branch FROM student_info ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Result</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <style>
    label{display:block;margin:8px 0 4px}
    input,select{width:100%;padding:8px;border:1px solid #ddd;border-radius:6px}
    .button{background:#2d7ff9;color:#fff;border:none;padding:10px 14px;border-radius:6px;cursor:pointer}
  </style>
</head>
<body>
<div class="container">
  <div class="topnav">
    <div class="topnavleft">
      <a href="admin_home.php">Admin Home</a>
      <a href="view_results.php">Results</a>
      <a href="add_result.php" class="active">Add Result</a>
    </div>
    <div class="topnavright"><a href="logout.php">Logout</a></div>
  </div>

  <h2>Add Result</h2>
  <?php if ($message): ?><div class="alert error"><?= htmlspecialchars($message) ?></div><?php endif; ?>

  <form method="post">
    <label for="student_id">Select Student</label>
    <select name="student_id" id="student_id" required>
      <option value="">-- Select student --</option>
      <?php while ($s = $students->fetch(PDO::FETCH_ASSOC)): 
          $label = $s['name'];
          $details = [];
          if (strlen(trim($s['course'])))  $details[] = $s['course'];
          if (strlen(trim($s['branch'])))  $details[] = $s['branch'];
          if (!empty($details)) $label .= ' (' . implode(' - ', $details) . ')';
      ?>
        <option value="<?= htmlspecialchars($s['id']) ?>"><?= htmlspecialchars($label) ?></option>
      <?php endwhile; ?>
    </select>

    <label for="sports">Sports</label>
    <input type="text" name="sports" id="sports" required>

    <label for="location">Location</label>
    <input type="text" name="location" id="location">

    <label for="date">Date</label>
    <input type="date" name="date" id="date" required>

    <label for="position">Position</label>
    <select name="position" id="position" required>
      <option value="1st">1st</option>
      <option value="2nd">2nd</option>
      <option value="3rd">3rd</option>
    </select>

    <div style="margin-top:12px">
      <button class="button" type="submit">Add Result</button>
    </div>
  </form>
</div>
</body>
</html>
