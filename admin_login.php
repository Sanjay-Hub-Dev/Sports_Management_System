    <?php
    session_start();
    include_once("connection.php");
    $error = "";
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     $username = trim($_POST['name']);
    //     $password = $_POST['password'];
    //     $stmt = $conn->prepare("SELECT * FROM admin_info WHERE username = :u");
    //     $stmt->bindValue(':u', $username);
    //     $stmt->execute();
    //     $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    //     if ($admin && password_verify($password, $admin['password'])) {
    //         $_SESSION['username'] = $admin['username'];
    //         header("Location: admin_home.php");
    //         exit;
    //     } else {
    //         $error = "Login failed: invalid credentials.";
    //     }
    // }

    // //i change the code by setting the admin username and password both as "admin" without using database
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //     $username = trim($_POST['name']);
    //     $password = $_POST['password'];

    //     // Default admin credentials (no database)
    //     $default_username = "admin";
    //     $default_password = "admin"; // plain text (simple use case)

    //     if ($username === $default_username && $password === $default_password) {
    //         $_SESSION['username'] = $default_username;
    //         header("Location: admin_home.php");
    //         exit;
    //     } else {
    //         $error = "Login failed: invalid credentials.";
    //     }
    // }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['name']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT username, password FROM admin_info WHERE username = :u"
    );
    $stmt->bindValue(':u', $username);
    $stmt->execute();

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // PLAIN TEXT comparison
    if ($admin && $password === $admin['password']) {
        $_SESSION['username'] = $admin['username'];
        header("Location: admin_home.php");
        exit;
    } else {
        $error = "Login failed: invalid credentials.";
    }
}

    ?>


    ?>

    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Admin Login</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="container">
            <div class="topnav">
                <div class="brand">JBIET Sports Event</div>
                <div>
                    <a href="index.php">Home</a>
                </div>
            </div>
            <div class="card" style="max-width:450px;margin:auto;">
                <h2>Admin Login</h2>
                <?php if ($error): ?>
                    <div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                <form method="post">
                    <div class="field-grid">
                        <div>
                            <label for="name">Username</label>
                            <input name="name" id="name" required>
                        </div>
                        <div>
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required>
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <button class="button" type="submit">Login</button>
                    </div>
                </form>
                <p class="small"><a href="index.php">Back to selector</a></p>
            </div>
        </div>
    </body>

    </html>