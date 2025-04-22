<?php
session_start();

// Set your admin password
$adminPassword = "abc123";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredPassword = $_POST['password'];

    if ($enteredPassword === $adminPassword) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        $error = "Incorrect password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MU Transit Admin</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('img/background.png') no-repeat center center/cover;
            font-family: Arial, sans-serif;
        }
        .login-container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }
        .logo {
            width: 150px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            color: #008D62;
            margin-bottom: 20px;
        }
        .input-group {
            display: flex;
            align-items: center;
            border: 2px solid #008D62;
            border-radius: 50px;
            padding: 12px;
            margin-bottom: 20px;
            background: #fff;
        }
        .input-group input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            padding: 5px;
            -webkit-text-security: disc;
        }
        .login-btn {
            background: #008D62;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            box-shadow: 0px 4px 10px rgba(0, 141, 98, 0.3);
            transition: background 0.3s ease;
        }
        .login-btn:hover {
            background: #006f4e;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="img/mu_logo.png" alt="Marwadi University Logo" class="logo">
        <div class="title">MU Transit</div>
        <h1>Admin</h1>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST" action="">
            <div class="input-group">
                <input type="password" name="password" placeholder="Enter Password" required>
            </div>
            <button class="login-btn" type="submit">Login</button>
        </form>
    </div>
</body>
</html>
