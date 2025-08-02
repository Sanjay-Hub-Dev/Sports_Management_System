<?php
session_start();
// Optional: require student to be logged in to view
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Contact Support</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .contact-card {
      max-width: 480px;
      margin: 80px auto;
      background: #fff;
      padding: 24px;
      border-radius: 10px;
      box-shadow: 0 12px 36px -8px rgba(31,45,58,0.15);
      font-family: system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;
    }
    .contact-card h2 {
      margin-top: 0;
    }
    .info {
      margin: 12px 0;
      font-size: 16px;
    }
    .back {
      margin-top: 20px;
    }
    .back a {
      text-decoration:none;
      color:#2d7ff9;
      font-weight:600;
    }
  </style>
</head>
<body>
  <div class="contact-card">
    <h2>Contact for Help</h2>
    <p class="info"><strong>Email:</strong> sanjaydhavanam@gmail.com</p>
    <p class="info"><strong>Phone Number:</strong> 9988776655</p>
    <div class="back">
      <a href="user_show.php">&larr; Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
