<?php
session_start();
include_once("connection.php");
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: view_students.php");
    exit;
}

// Delete if requested
if (isset($_GET['delete'])) {
    $del = $conn->prepare("DELETE FROM student_info WHERE id = :id");
    $del->bindValue(':id', $id);
    $del->execute();
    header("Location: view_students.php?msg=deleted");
    exit;
}

// Fetch current student
$stmt = $conn->prepare("SELECT * FROM student_info WHERE id = :id");
$stmt->bindValue(':id', $id);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$student) {
    echo "Student not found.";
    exit;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update = $conn->prepare("UPDATE student_info SET 
        name = :name,
        email = :email,
        contact = :contact,
        gender = :gender,
        DOB = :dob,
        department = :course,
        branch = :branch,
        year = :year
        WHERE id = :id
    ");
    $success = $update->execute([
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':contact' => $_POST['contact'],
        ':gender' => $_POST['gender'],
        ':dob' => $_POST['DOB'],
        ':course' => $_POST['department'], // Course
        ':branch' => $_POST['branch'],
        ':year' => $_POST['year'],
        ':id' => $id
    ]);
    if ($success) {
        $message = "✅ Updated successfully.";
        // refresh
        $stmt->execute([':id' => $id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "❌ Update failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Student</title>
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

    <h2>Edit Student</h2>
    <?php if ($message): ?>
      <div class="message <?= strpos($message, 'Updated') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="post">
      <div class="flex">
        <div>
          <label for="name">Name</label>
          <input name="name" id="name" required value="<?= htmlspecialchars($student['name']) ?>">
        </div>
        <div>
          <label for="email">Email</label>
          <input name="email" id="email" required value="<?= htmlspecialchars($student['email']) ?>">
        </div>
        <div>
          <label for="contact">Contact</label>
          <input name="contact" id="contact" value="<?= htmlspecialchars($student['contact']) ?>">
        </div>
        <div>
          <label for="gender">Gender</label>
          <select name="gender" id="gender">
            <option value="Male" <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
          </select>
        </div>
        <div>
          <label for="DOB">DOB</label>
          <input type="date" name="DOB" id="DOB" value="<?= htmlspecialchars($student['DOB']) ?>">
        </div>
        <div>
          <label for="department">Course</label>
          <select name="department" id="department" required>
            <option value="B_Tech" <?= $student['department'] === 'B_Tech' ? 'selected' : '' ?>>B.Tech</option>
            <option value="BCA" <?= $student['department'] === 'BCA' ? 'selected' : '' ?>>BCA</option>
            <option value="M_Tech" <?= $student['department'] === 'M_Tech' ? 'selected' : '' ?>>M.Tech</option>
            <option value="MCA" <?= $student['department'] === 'MCA' ? 'selected' : '' ?>>MCA</option>
          </select>
        </div>
        <div>
          <label for="branch">Branch</label>
          <input name="branch" id="branch" value="<?= htmlspecialchars($student['branch'] ?? '') ?>">
        </div>
        <div>
          <label for="year">Year</label>
          <select name="year" id="year">
            <option value="1styear" <?= $student['year'] === '1styear' ? 'selected' : '' ?>>1st Year</option>
            <option value="2ndyear" <?= $student['year'] === '2ndyear' ? 'selected' : '' ?>>2nd Year</option>
            <option value="3rdyear" <?= $student['year'] === '3rdyear' ? 'selected' : '' ?>>3rd Year</option>
            <option value="4thyear" <?= $student['year'] === '4thyear' ? 'selected' : '' ?>>4th Year</option>
          </select>
        </div>
      </div>

      <div style="margin-top:16px;">
        <button type="submit" class="btn primary">Save Changes</button>
        <a href="view_students.php" class="btn" style="background:#6c757d; color:#fff; text-decoration:none;">Cancel</a>
        <a href="edit_student.php?id=<?= urlencode($id) ?>&delete=1" onclick="return confirm('Are you sure you want to delete this student?')" class="btn danger">Delete</a>
      </div>
    </form>
  </div>
</body>
</html>
