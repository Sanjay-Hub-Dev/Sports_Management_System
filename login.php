<?php
session_start();
include_once("connection.php");
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id']);
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM student_info WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        header("Location: user_show.php");
        exit;
    } else {
        $error = "Login failed: invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="topnav">
            <div class="brand">JBIET Sports Event</div>
            <div>
                <a href="index.php">Home</a>
                <a href="sign-up.php">SignUp</a>
            </div>
        </div>
        <div class="card" style="max-width:500px;margin:auto;">
            <h2>Student Login</h2>
            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="post">
                <div class="field-grid">
                    <div>
                        <label for="id">Student ID</label>
                        <input type="text" name="id" id="id" required>
                    </div>
                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                </div>
                <div style="margin-top:16px;">
                    <button type="submit" class="button">Login</button>
                </div>
            </form>
            <p class="small">New? <a href="sign-up.php">Sign up</a></p>
            <p class="small"><a href="index.php">Back to selector</a></p>
        </div>
    </div>
</body>

</html>