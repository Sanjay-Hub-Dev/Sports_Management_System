<?php
session_start();
include_once("connection.php");

// If already logged in, redirect appropriately
if (isset($_SESSION['username'])) {
    header("Location: admin_home.php");
    exit;
}
if (isset($_SESSION['id'])) {
    header("Location: user_show.php");
    exit;
}

$login_error = "";
$signup_error = "";
$signup_success = "";

// HANDLE LOGIN
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $identifier = trim($_POST['identifier']); // admin username or student id
    $password = $_POST['password'] ?? '';

    // First try admin
    $stmt = $conn->prepare("SELECT * FROM admin_info WHERE username = :u");
    $stmt->bindValue(':u', $identifier);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['username'] = $admin['username'];
        header("Location: admin_home.php");
        exit;
    }

    // Next try student (id)
    $stmt2 = $conn->prepare("SELECT * FROM student_info WHERE id = :id");
    $stmt2->bindValue(':id', $identifier);
    $stmt2->execute();
    $user = $stmt2->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        header("Location: user_show.php");
        exit;
    }

    $login_error = "Login failed: invalid credentials.";
}

// HANDLE SIGNUP (student only)
if (isset($_POST['action']) && $_POST['action'] === 'signup') {
    // Basic required fields
    $sname = trim($_POST['name']);
    $sid = trim($_POST['id']);
    $semail = trim($_POST['email']);
    $snumber = trim($_POST['ph_number']);
    $sgender = $_POST['gender'] ?? '';
    $spassword = $_POST['password'] ?? '';
    $scpassword = $_POST['c_password'] ?? '';
    $sdob = $_POST['DOB'] ?? null;
    $sdepartment = $_POST['department'] ?? '';
    $syear = $_POST['year'] ?? '';
    $ssports = isset($_POST['sports']) ? implode(",", $_POST['sports']) : '';
    $splayer = $_POST['player'] ?? '';

    if ($spassword !== $scpassword) {
        $signup_error = "Passwords do not match.";
    } else {
        // Optional: check if student ID already exists
        $check = $conn->prepare("SELECT id FROM student_info WHERE id = :id");
        $check->bindValue(':id', $sid);
        $check->execute();
        if ($check->fetch()) {
            $signup_error = "Student ID already registered.";
        } else {
            $photo = '';
            if (!empty($_FILES["myfile"]["name"])) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
                $photo = uniqid('dp_') . "_" . basename($_FILES["myfile"]["name"]);
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $target_dir . $photo);
            }
            $hashed = password_hash($spassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO student_info 
                (name, id, email, contact, gender, password, DOB, department, year, sports, player, photo)
                VALUES (:a,:b,:c,:d,:e,:f,:g,:h,:i,:j,:k,:l)");
            $stmt->bindValue(':a', $sname);
            $stmt->bindValue(':b', $sid);
            $stmt->bindValue(':c', $semail);
            $stmt->bindValue(':d', $snumber);
            $stmt->bindValue(':e', $sgender);
            $stmt->bindValue(':f', $hashed);
            $stmt->bindValue(':g', $sdob);
            $stmt->bindValue(':h', $sdepartment);
            $stmt->bindValue(':i', $syear);
            $stmt->bindValue(':j', $ssports);
            $stmt->bindValue(':k', $splayer);
            $stmt->bindValue(':l', $photo);
            if ($stmt->execute()) {
                $signup_success = "Signup successful. You can now login.";
            } else {
                $signup_error = "Signup failed. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Authenticate - Sports Management</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .split { display: inline-block; vertical-align: top; width: 48%; margin:1%; }
    fieldset { padding: 10px; }
    .error { color: red; }
    .success { color: green; }
  </style>
</head>
<body>
<div class="container">
  <h1>Welcome to Sports Management System</h1>
  <div style="display:flex; gap:20px; flex-wrap:wrap;">
    <!-- LOGIN -->
    <div class="split">
      <fieldset>
        <legend><strong>Login (User / Admin)</strong></legend>
        <?php if ($login_error): ?><p class="error"><?= htmlspecialchars($login_error) ?></p><?php endif; ?>
        <form method="post">
          <input type="hidden" name="action" value="login">
          <label for="identifier">Username / ID:</label><br>
          <input type="text" name="identifier" id="identifier" required placeholder="Admin username or Student ID"><br><br>
          <label for="password">Password:</label><br>
          <input type="password" name="password" id="password" required><br><br>
          <input type="submit" value="Login">
        </form>
      </fieldset>
    </div>

    <!-- SIGNUP (student only) -->
    <div class="split">
      <fieldset>
        <legend><strong>Student Signup</strong></legend>
        <?php if ($signup_error): ?><p class="error"><?= htmlspecialchars($signup_error) ?></p><?php endif; ?>
        <?php if ($signup_success): ?><p class="success"><?= htmlspecialchars($signup_success) ?></p><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="signup">
          <label>Name</label><br><input name="name" required><br><br>
          <label>ID</label><br><input name="id" required><br><br>
          <label>Email</label><br><input type="email" name="email" required><br><br>
          <label>Contact</label><br><input name="ph_number"><br><br>
          <label>Gender</label><br>
            <input type="radio" name="gender" value="Male" required>Male
            <input type="radio" name="gender" value="Female">Female<br><br>
          <label>Password</label><br><input type="password" name="password" required><br><br>
          <label>Confirm Password</label><br><input type="password" name="c_password" required><br><br>
          <label>DOB</label><br><input type="date" name="DOB"><br><br>
          <label>Department</label><br>
            <select name="department">
              <option value="B_Tech">B.Tech</option>
              <option value="BCA">BCA</option>
            </select><br><br>
          <label>Year</label><br>
            <select name="year">
              <option value="1styear">1st Year</option>
              <option value="2ndyear">2nd Year</option>
            </select><br><br>
          <label>Sports</label><br>
            <input type="checkbox" name="sports[]" value="Cricket">Cricket
            <input type="checkbox" name="sports[]" value="Football">Football<br><br>
          <label>Player Role</label><br>
            <input type="radio" name="player" value="Player1">Player1
            <input type="radio" name="player" value="Player2">Player2<br><br>
          <label>Photo</label><br><input type="file" name="myfile"><br><br>
          <input type="submit" value="Sign Up">
        </form>
      </fieldset>
    </div>
  </div>

  <p>If you're admin, use the login part with your admin username (e.g., "admin") and password.</p>
</div>
</body>
</html>
