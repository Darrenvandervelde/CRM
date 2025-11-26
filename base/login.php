<?php
session_start();
require "../base/system/db/db.php"; // Ensure this creates $pdo (PDO)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get input
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate
    if ($username === '' || $password === '') {
        header("Location: login.php?error=" . urlencode("Please enter username and password."));
        exit();
    }

    // Fetch user
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        header("Location: login.php?error=" . urlencode("Invalid username or password."));
        exit();
    }

    // Save session
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];

    // Redirect based on role
    switch ($user['role']) {
        case "admin":
            header("Location: ../admin/index.php");
            break;
        case "client":
            header("Location: ../client/index.php");
            break;
        case "dev":
            header("Location: ../dev/index.php");
            break;
        default:
            header("Location: login.php?error=" . urlencode("Unknown role."));
            break;
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* FULL PAGE BACKGROUND */
body {
    height: 100vh;
    background: #111;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

/* FULL PAGE BACKGROUND LINES */
.triangle-bg {
    position: absolute;
    inset: 0;
    background: 
        repeating-linear-gradient(20deg, rgba(255,255,255,0.1) 0px, rgba(253,253,253,0.1) 2px, transparent 2px, transparent 100px),
        repeating-linear-gradient(40deg, rgba(255,255,255,0.1) 0px, rgba(161,161,161,0.1) 2px, transparent 2px, transparent 100px),
        repeating-linear-gradient(60deg, rgba(255,255,255,0.1) 0px, rgba(255,255,255,0.1) 2px, transparent 2px, transparent 100px),
        repeating-linear-gradient(80deg, rgba(255,255,255,0.05) 0px, rgba(161,161,161,0.05) 2px, transparent 2px, transparent 100px),
        repeating-linear-gradient(100deg, rgba(255,255,255,0.05) 0px, rgba(161,161,161,0.05) 2px, transparent 2px, transparent 100px),
        repeating-linear-gradient(120deg, rgba(252,252,252,0.05) 0px, rgba(255,255,255,0.05) 2px, transparent 2px, transparent 100px),
        repeating-linear-gradient(140deg, rgba(252,252,252,0.05) 0px, rgba(255,255,255,0.05) 2px, transparent 2px, transparent 100px),
        repeating-linear-gradient(160deg, rgba(252,252,252,0.05) 0px, rgba(255,255,255,0.05) 2px, transparent 2px, transparent 100px),
        repeating-linear-gradient(180deg, rgba(255,255,255,0.05) 0px, rgba(255,255,255,0.05) 2px, transparent 2px, transparent 100px),
        repeating-linear-gradient(200deg, rgba(252,252,252,0.05) 0px, rgba(255,255,255,0.05) 2px, transparent 2px, transparent 100px);
    backdrop-filter: blur(2px);
    opacity: 0.8;
    box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.1);
}

/* LOGIN CONTAINER */
.login-wrapper {
    width: 400px;
    height: 100px;
    padding: 40px;
    background: rgba(255,255,255,0.85);
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(158,158,158,0.2);
    backdrop-filter: blur(8px);
    position: relative;
    transition: all 0.3s ease-in-out;
}

/* Expand on hover */
.login-wrapper:hover {
    width: 400px;
    height: 600px;
    box-shadow: 0 12px 40px rgba(255,255,255,0.05);
}

/* Decorative soft glow behind */
.login-wrapper::after {
    content: '';
    position: absolute;
    inset: -20px;
    border-radius: 20px;
    background: rgba(255,255,255,0.08);
    filter: blur(30px);
    z-index: -1;
}
.login-wrapper h1 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.login-title {
    text-align: center;
    font-size: 46px;
    font-family: "Orbitron", sans-serif;
    font-weight: 700;
    position: relative;
    transition: all 0.3s ease;
}

/* Pseudo-element shows hover text */
.login-title::after {
    content: attr(data-hover);
    position: absolute;
    left: 50%;
    top: 0;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

/* On hover, hide original text, show new text */
.login-wrapper:hover .login-title {
    color: transparent; /* hides original text */
}

.login-wrapper:hover .login-title::after {
    opacity: 1;
    color: #4A6CF7;
}
    </style>
    </head>

    <body>
        <div id="triangle-bg" class="triangle-bg"></div>

    <div class="login-wrapper">
        <h1 class="login-title" data-hover="Welcome Back!">Login</h1>

        <form action="" method="POST">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>


</body>
</html>
