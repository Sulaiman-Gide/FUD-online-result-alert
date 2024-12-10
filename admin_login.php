<?php
session_start();

$defaultAdminUsername = 'admin';
$defaultAdminPassword = 'adminpassword';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $defaultAdminUsername && $password === $defaultAdminPassword) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f0f4f8;
            font-family: Arial, sans-serif;
        }

        .login {
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 40%;
            padding: 20px;
            box-sizing: border-box;
        }

        .login h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .login p {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .login .form-group {
            margin-bottom: 20px;
        }

        .login input[type="text"],
        .login input[type="password"] {
            width: 95%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #333;
        }

        .login button {
            width: 100%;
            padding: 15px;
            background: #1A76D1;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login button:hover {
            background: #145b9c;
        }

        .login .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .login .lost-pass {
            display: block;
            text-align: right;
            color: #1A76D1;
            font-size: 14px;
            text-decoration: none;
        }

        .login .lost-pass:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login">
        <h2>Admin Login</h2>
        <p>Please login to your admin account.</p>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
