<?php
session_start();
include_once("connection.php");

$signup_error = "";
$signup_success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sname      = trim($_POST['name']);
    $sid        = trim($_POST['id']);
    $semail     = trim($_POST['email']);
    $snumber    = trim($_POST['ph_number']);
    $sgender    = $_POST['gender'] ?? '';
    $spassword  = $_POST['password'] ?? '';
    $scpassword = $_POST['c_password'] ?? '';
    $sdob       = $_POST['DOB'] ?? null;
    $scourse    = $_POST['department'] ?? ''; // Course stored in 'department' column
    $sbranch    = trim($_POST['branch'] ?? '');
    $syear      = $_POST['year'] ?? '';
    $ssports_arr = isset($_POST['sports']) ? $_POST['sports'] : [];
    $ssports     = implode(",", $ssports_arr);
    $splayer     = $_POST['player'] ?? '';

    if ($spassword !== $scpassword) {
        $signup_error = "Passwords do not match.";
    } elseif (empty($sname) || empty($sid) || empty($spassword) || empty($scourse)) {
        $signup_error = "Required fields (Name, ID, Password, Course) cannot be empty.";
    } else {
        // check if student already exists
        $chk = $conn->prepare("SELECT id FROM student_info WHERE id = :id");
        $chk->bindValue(':id', $sid);
        $chk->execute();
        if ($chk->fetch()) {
            $signup_error = "Student ID already registered.";
        } else {
            // default branch to course if empty
            if (empty($sbranch)) {
                $sbranch = $scourse;
            }

            $photo = '';
            if (!empty($_FILES["myfile"]["name"])) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
                $photo = uniqid('dp_') . "_" . basename($_FILES["myfile"]["name"]);
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $target_dir . $photo);
            }

            $hashed = password_hash($spassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO student_info 
                (name, id, email, contact, gender, password, DOB, department, branch, year, sports, player, photo)
                VALUES (:name, :id, :email, :contact, :gender, :password, :DOB, :department, :branch, :year, :sports, :player, :photo)");
            $stmt->bindValue(':name', $sname);
            $stmt->bindValue(':id', $sid);
            $stmt->bindValue(':email', $semail);
            $stmt->bindValue(':contact', $snumber);
            $stmt->bindValue(':gender', $sgender);
            $stmt->bindValue(':password', $hashed);
            $stmt->bindValue(':DOB', $sdob);
            $stmt->bindValue(':department', $scourse);
            $stmt->bindValue(':branch', $sbranch);
            $stmt->bindValue(':year', $syear);
            $stmt->bindValue(':sports', $ssports);
            $stmt->bindValue(':player', $splayer);
            $stmt->bindValue(':photo', $photo);

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
  <title>Student Signup</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <style>
    .container { max-width: 900px; margin:40px auto; }
    fieldset { padding:16px; border-radius:8px; }
    legend { font-weight:bold; }
    label { display:block; margin-top:8px; font-weight:600; }
    input, select { width:100%; padding:8px; margin-top:4px; border:1px solid #cbd5e1; border-radius:6px; box-sizing:border-box; }
    .flex { display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
    .alert { padding:10px; border-radius:6px; margin-bottom:12px; }
    .error { background:#ffe3e3; color:#a12d2d; }
    .success { background:#e3f7e3; color:#27632a; }
    .actions { margin-top:16px; }
    .button { background:#2d7ff9; color:#fff; border:none; padding:10px 16px; border-radius:6px; cursor:pointer; font-weight:600; }
    .small { font-size:12px; color:#555; }
  </style>
</head>
<body>
  <div class="container">
    <div class="topnav">
      <div class="topnavleft"><a href="index.php">Home</a></div>
    </div>

    <h2>Student Signup</h2>
    <?php if ($signup_error): ?>
      <div class="alert error"><?= htmlspecialchars($signup_error) ?></div>
    <?php endif; ?>
    <?php if ($signup_success): ?>
      <div class="alert success"><?= htmlspecialchars($signup_success) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <fieldset>
        <legend>Personal Details</legend>
        <div class="flex">
          <div>
            <label for="name">Name</label>
            <input name="name" id="name" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
          </div>
          <div>
            <label for="id">ID</label>
            <input name="id" id="id" required value="<?= isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '' ?>">
          </div>
          <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
          </div>
          <div>
            <label for="ph_number">Contact</label>
            <input name="ph_number" id="ph_number" value="<?= isset($_POST['ph_number']) ? htmlspecialchars($_POST['ph_number']) : '' ?>">
          </div>
          <div>
            <label>Gender</label>
            <div style="display:flex; gap:10px;">
              <label><input type="radio" name="gender" value="Male" <?= (isset($_POST['gender']) && $_POST['gender']==='Male') ? 'checked' : '' ?> required> Male</label>
              <label><input type="radio" name="gender" value="Female" <?= (isset($_POST['gender']) && $_POST['gender']==='Female') ? 'checked' : '' ?>> Female</label>
            </div>
          </div>
          <div>
            <label for="DOB">Date of Birth</label>
            <input type="date" name="DOB" id="DOB" value="<?= isset($_POST['DOB']) ? htmlspecialchars($_POST['DOB']) : '' ?>">
          </div>
          <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
          </div>
          <div>
            <label for="c_password">Confirm Password</label>
            <input type="password" name="c_password" id="c_password" required>
          </div>
          <div>
            <label for="myfile">Photo</label>
            <input type="file" name="myfile" id="myfile">
          </div>
        </div>
      </fieldset>

      <fieldset style="margin-top:16px;">
        <legend>Academic & Participation</legend>
        <div class="flex">
          <div>
            <label for="department">Course</label>
            <select name="department" id="department" required>
              <option value="">--Select Course--</option>
              <option value="B_Tech" <?= (isset($_POST['department']) && $_POST['department']==='B_Tech') ? 'selected' : '' ?>>B.Tech</option>
              <option value="BCA" <?= (isset($_POST['department']) && $_POST['department']==='BCA') ? 'selected' : '' ?>>BCA</option>
              <option value="M_Tech" <?= (isset($_POST['department']) && $_POST['department']==='M_Tech') ? 'selected' : '' ?>>M.Tech</option>
              <option value="MCA" <?= (isset($_POST['department']) && $_POST['department']==='MCA') ? 'selected' : '' ?>>MCA</option>
            </select>
          </div>
          <div>
            <label for="branch">Branch</label>
            <input type="text" name="branch" id="branch" placeholder="e.g., CSE, IT" value="<?= isset($_POST['branch']) ? htmlspecialchars($_POST['branch']) : '' ?>">
          </div>
          <div>
            <label for="year">Year</label>
            <select name="year" id="year">
              <option value="1styear" <?= (isset($_POST['year']) && $_POST['year']==='1styear') ? 'selected' : '' ?>>1st Year</option>
              <option value="2ndyear" <?= (isset($_POST['year']) && $_POST['year']==='2ndyear') ? 'selected' : '' ?>>2nd Year</option>
              <option value="3rdyear" <?= (isset($_POST['year']) && $_POST['year']==='3rdyear') ? 'selected' : '' ?>>3rd Year</option>
              <option value="4thyear" <?= (isset($_POST['year']) && $_POST['year']==='4thyear') ? 'selected' : '' ?>>4th Year</option>
            </select>
          </div>
          
        </div>
      </fieldset>

      <div class="actions">
        <button type="submit" class="button">Sign Up</button>
        <button type="reset" class="button secondary" style="background:#6c757d;">Reset</button>
      </div>
      <p class="small">Already registered? <a href="login.php">Login here</a></p>
    </form>
  </div>
</body>
</html>
