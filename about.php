<?php
session_start();
include_once("connection.php");

// Determine if admin or student is logged in (for nav)
$isAdmin = isset($_SESSION['username']);
$isStudent = isset($_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>About - JBIET Sports Management</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .hero {
      position: relative;
      padding: 120px 24px 80px;
      background: linear-gradient(135deg, rgba(31,59,112,0.85) 0%, rgba(45,127,249,0.65) 70%), url('https://images.unsplash.com/photo-1508606572321-901ea4437073?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
      color: white;
      border-radius: 12px;
      margin-bottom: 32px;
    }
    .hero h1 {
      font-size: 2.8rem;
      margin: 0 0 8px;
      letter-spacing: 0.5px;
    }
    .hero p {
      font-size: 1.1rem;
      margin: 6px 0 16px;
      max-width: 800px;
    }
    .pill {
      display: inline-block;
      background: #ffb400;
      color: #1f2d44;
      padding: 6px 14px;
      border-radius: 999px;
      font-weight: 700;
      font-size: 0.8rem;
      margin-bottom: 8px;
    }
    .section {
      margin-top: 40px;
    }
    .cards {
      display: grid;
      gap: 20px;
      grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
      margin-top: 12px;
    }
    .card-small {
      background:#fff;
      border-radius:10px;
      padding:16px 18px;
      box-shadow:0 14px 40px -12px rgba(31,45,88,0.08);
    }
    .feature-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .feature-list li {
      margin: 8px 0;
      padding-left: 22px;
      position: relative;
    }
    .feature-list li:before {
      content: "✔";
      position: absolute;
      left: 0;
      top: 0;
      color: #2d7ff9;
      font-weight: bold;
    }
    .stack {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }
    .badge {
      background: #eef6ff;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 0.85rem;
      margin-right: 6px;
      display: inline-block;
    }
    .ack {
      background: #f9f9fb;
      padding: 16px;
      border-left: 4px solid #ffb400;
      border-radius: 6px;
      margin-top: 12px;
    }

    .hero p {
      color: black;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="topnav">
      <div class="topnavleft">
        <a href="index.php">Home</a>
        <a href="about.php" class="active">About</a>
        <?php if ($isAdmin): ?>
          <a href="admin_home.php">Admin Home</a>
        <?php elseif ($isStudent): ?>
          <a href="user_show.php">Dashboard</a>
        <?php endif; ?>
      </div>
      <div class="topnavright">
        <?php if (!$isAdmin && !$isStudent): ?>
          <a href="index.php">Login / Signup</a>
        <?php endif; ?>
        <a href="contact_user.php">Contact</a>
        <?php if ($isAdmin || $isStudent): ?>
          <a href="logout.php">Logout</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="hero">
      <div class="pill">J.B.I.E.T Sports Event</div>
      <h1>Sports Management System</h1>
      <p>A web-based platform designed to simplify sports event organization. Students can register. Admins manage participants and events efficiently. Secure handling and centralized coordination reduce paperwork and errors.</p>
      <div style="display:flex; gap:14px; flex-wrap:wrap; margin-top:16px;">
        <div class="card-small" style="flex:1;">
          <h3>Presented By</h3>
          <p><strong>D. Sanjay</strong><br>Roll Number: 22671A1210<br>Department of Information Technology<br>JBIET</p>
        </div>
        <div class="card-small" style="flex:1;">
          <h3>Academic Year</h3>
          <p>2024–2025</p>
        </div>
      </div>
    </div>

    <div class="section">
      <h2>Problem Statement</h2>
      <p>Manual registration processes are time-consuming, error-prone, and require students to physically visit venues. There is a high risk of mismanagement and data loss. A centralized digital solution is urgently needed.</p>
    </div>

    <div class="section">
      <h2>Objectives</h2>
      <div class="cards">
        <div class="card-small">
          <h4>1. Online Registration</h4>
          <p>Enable students to register for sports events easily online.</p>
        </div>
        <div class="card-small">
          <h4>2. Admin Management</h4>
          <p>Provide administrators efficient dashboards to manage events and participants.</p>
        </div>
        <div class="card-small">
          <h4>3. Secure Storage</h4>
          <p>Store all student and event information securely in a centralized database.</p>
        </div>
        <div class="card-small">
          <h4>4. Efficiency</h4>
          <p>Reduce manual workload and improve coordination.</p>
        </div>
      </div>
    </div>

    <div class="section">
      <h2>System Features</h2>
      <ul class="feature-list">
        <li>User Access with secure login & registration</li>
        <li>Sport Selection (Indoor/Outdoor)</li>
        <li>Admin Control Panel</li>
        <li>Player Roles</li>
        <li>Profile photo upload</li>
        <li>Data Security with encrypted passwords</li>
      </ul>
    </div>

    <div class="section">
      <h2>Technology Stack</h2>
      <div class="stack">
        <div class="badge">HTML</div>
        <div class="badge">CSS</div>
        <div class="badge">PHP</div>
        <div class="badge">MySQL</div>
        <div class="badge">XAMPP</div>
        <div class="badge">phpMyAdmin</div>
      </div>
    </div>

    <div class="section">
      <h2>System Architecture (Summary)</h2>
      <div class="cards">
        <div class="card-small">
          <h4>Student Interaction</h4>
          <p>Students register/login and choose sports/events.</p>
        </div>
        <div class="card-small">
          <h4>Form Submission</h4>
          <p>Data submitted securely via forms.</p>
        </div>
        <div class="card-small">
          <h4>Data Storage</h4>
          <p>Information saved in MySQL database.</p>
        </div>
        <div class="card-small">
          <h4>Admin Management</h4>
          <p>Admins view and control events, participants, and results.</p>
        </div>
      </div>
    </div>

    <div class="section">
      <h2>Conclusion & Acknowledgment</h2>
      <p>The system significantly improves sports management in colleges by reducing time, effort, and manual errors.</p>
    </div>
  </div>
</body>
</html>
