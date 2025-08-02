<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO results (student_name, course, department, roll_number, location, date, position) 
                            VALUES (:name, :course, :dept, :roll, :loc, :date, :pos)");
    if ($stmt->execute([
        ':name' => $_POST['student_name'],
        ':course' => $_POST['course'],
        ':dept' => $_POST['department'],
        ':roll' => $_POST['roll_number'],
        ':loc' => $_POST['location'],
        ':date' => $_POST['date'],
        ':pos' => $_POST['position']
    ])) {
        $message = "✅ Result added successfully.";
    } else {
        $message = "❌ Failed to add result.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Result</title></head>
<body>
<h2>Add Result</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="post">
    Name: <input name="student_name" required><br>
    Course: <input name="course"><br>
    Department: <input name="department"><br>
    Roll Number: <input name="roll_number"><br>
    Location: <input name="location"><br>
    Date: <input type="date" name="date"><br>
    Position: <input name="position"><br>
    <button type="submit">Add Result</button>
</form>
<a href="view_results.php">Back</a>
</body>
</html>
