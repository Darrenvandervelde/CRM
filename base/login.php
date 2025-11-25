<?php
session_start();

// Include database connection
require "../system/db/db.php"; // Make sure this returns $pdo (PDO connection)

if (isset($_POST['submit'])) {

    // Get form values safely
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate empty fields
    if (empty($username) || empty($password)) {
        echo "Please enter username and password";
        exit();
    }

    // Fetch user from DB
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check user exists
    if (!$user) {
        echo "Invalid username or password";
        exit();
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        echo "Invalid username or password";
        exit();
    }

    // Store session data
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['username'] = $user['username'];

    // Redirect based on role
    if ($user['role'] == "admin") {
        header("Location: ../admin/index.php");
        exit();
    } elseif ($user['role'] == "client") {
        header("Location: ../client/index.php");
        exit();
    } elseif ($user['role'] == "dev") {
        header("Location: ../dev/index.php");
        exit();
    } else {
        echo "Unknown role!";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #fff;
            padding: 30px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 17px;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }
    </style>

</head>
<body>

<div class="login-container">
    <h2>Login</h2>

    <!-- Display errors if needed -->
    <?php if (isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="submit">Login</button>
    </form>

    <div class="footer">
        © <?= date('Y'); ?> Your System
    </div>
</div>

</body>
</html>



