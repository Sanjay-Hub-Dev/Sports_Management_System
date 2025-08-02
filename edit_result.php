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

// Delete
if (isset($_GET['delete'])) {
    $conn->prepare("DELETE FROM results WHERE result_id = :id")->execute([':id' => $id]);
    header("Location: view_results.php?msg=deleted");
    exit;
}

// Fetch
$stmt = $conn->prepare("SELECT * FROM results WHERE result_id = :id");
$stmt->execute([':id' => $id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update = $conn->prepare("UPDATE results SET student_name=:name, course=:course, department=:dept, roll_number=:roll, location=:loc, date=:date, position=:pos WHERE result_id=:id");
    if ($update->execute([
        ':name' => $_POST['student_name'],
        ':course' => $_POST['course'],
        ':dept' => $_POST['department'],
        ':roll' => $_POST['roll_number'],
        ':loc' => $_POST['location'],
        ':date' => $_POST['date'],
        ':pos' => $_POST['position'],
        ':id' => $id
    ])) {
        $message = "✅ Updated successfully.";
    } else {
        $message = "❌ Update failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Result</title></head>
<body>
<h2>Edit Result</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="post">
    Name: <input name="student_name" value="<?= htmlspecialchars($result['student_name']) ?>"><br>
    Course: <input name="course" value="<?= htmlspecialchars($result['course']) ?>"><br>
    Department: <input name="department" value="<?= htmlspecialchars($result['department']) ?>"><br>
    Roll Number: <input name="roll_number" value="<?= htmlspecialchars($result['roll_number']) ?>"><br>
    Location: <input name="location" value="<?= htmlspecialchars($result['location']) ?>"><br>
    Date: <input type="date" name="date" value="<?= $result['date'] ?>"><br>
    Position: <input name="position" value="<?= htmlspecialchars($result['position']) ?>"><br>
    <button type="submit">Save Changes</button>
</form>
<a href="view_results.php">Back</a>
</body>
</html>
