<?php
session_start();
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}
// Redirect if already authenticated
if (isset($_SESSION['username'])) {
    header('Location: admin_home.php');
    exit;
}
if (isset($_SESSION['id'])) {
    header('Location: user_show.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>JBIET Sports Event</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            margin: 0;
        }

        .box {
            max-width: 500px;
            margin: 80px auto;
            background: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-top: 0;
        }

        .nav {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 16px 0;
        }

        .nav a {
            text-decoration: none;
            padding: 10px 16px;
            background: #2d7ff9;
            color: #fff;
            border-radius: 6px;
            font-weight: 600;
        }

        .small {
            font-size: 0.85em;
            margin-top: 8px;
        }

        .logout {
            background: #555;
        }
    </style>
</head>

<body>
    <div class="box">
        <div class="hero">
            <div>
                <div class="badge">JBIET Sports Event</div>
                <h1>Welcome to the Sports Management System</h1>
                <p>Centralized portal for students and admins to register, manage and view sports events with ease and
                    professionalism.</p>
            </div>
        </div>

        <div class="nav">
            <a href="login.php">Login</a>
            <a href="sign-up.php">SignUp</a>
            <a href="admin_login.php">Admin</a>
            <?php if (isset($_SESSION['id']) || isset($_SESSION['username'])): ?>
                <a class="logout" href="index.php?logout=1">Logout</a>
            <?php endif; ?>
        </div>
        <p class="small">If you are a student, use Login / SignUp. Admins use Admin login. After login you’ll be
            redirected to your dashboard automatically.</p>
    </div>
</body>

</html>