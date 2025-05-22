<?php
session_start();
require 'db.php';

$username = '';
$password = '';
$error = '';

if (isset($_COOKIE['remembered_username']) && empty($_POST)) {
    $username = $_COOKIE['remembered_username'];
}
if (isset($_COOKIE['remembered_password']) && empty($_POST)) {
    $password = $_COOKIE['remembered_password'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if ($username !== '' && $password !== '') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['staff_name'] = $username;
            if ($remember) {
                setcookie('remembered_username', $username, time() + (86400 * 30), "/"); 
                setcookie('remembered_password', $password, time() + (86400 * 30), "/"); 
            } else {
                setcookie('remembered_username', '', time() - 3600, "/");
                setcookie('remembered_password', '', time() - 3600, "/");
            }
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please enter both username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #181c2f 0%, #6366f1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 96vw;
            margin: 0 auto;
            background: rgba(30, 41, 59, 0.97);
            border-radius: 22px;
            box-shadow: 0 8px 32px 0 rgba(99,102,241,0.18), 0 1.5px 8px 0 #181c2f44;
            padding: 2.8rem 2.5rem 2.2rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 22px;
            background: linear-gradient(120deg, #818cf8 0%, #6366f1 100%);
            opacity: 0.13;
            z-index: 0;
        }
        .login-card h1 {
            color: #fff;
            margin-bottom: 2rem;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            z-index: 1;
            position: relative;
            text-shadow: 0 2px 12px #6366f1cc;
        }
        .login-card label {
            display: block;
            margin-bottom: 0.5rem;
            color: #c7d2fe;
            font-weight: 600;
            text-align: left;
            margin-top: 1.2rem;
            letter-spacing: 0.5px;
            z-index: 1;
            position: relative;
        }
        .login-card input[type="text"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 1rem 1.1rem;
            margin-bottom: 0.2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.13rem;
            background: rgba(99,102,241,0.09);
            color: #fff;
            box-shadow: 0 2px 8px rgba(99,102,241,0.08);
            outline: none;
            transition: background 0.2s, box-shadow 0.2s, border 0.2s;
            z-index: 1;
            position: relative;
        }
        .login-card input[type="text"]:focus,
        .login-card input[type="password"]:focus {
            background: rgba(129,140,248,0.18);
            box-shadow: 0 0 0 2px #6366f1cc;
            border: 1.5px solid #818cf8;
        }
        .login-card input[type="text"]::placeholder,
        .login-card input[type="password"]::placeholder {
            color: #a5b4fc;
            opacity: 1;
        }
        .login-card .remember-row {
            display: flex;
            align-items: center;
            margin-top: 1.1rem;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #a5b4fc;
            justify-content: flex-start;
            z-index: 1;
            position: relative;
        }
        .login-card .remember-row input[type="checkbox"] {
            margin-right: 0.5rem;
            accent-color: #6366f1;
            width: 1.1em;
            height: 1.1em;
        }
        .login-card .btn {
            width: 100%;
            margin-top: 1.7rem;
            padding: 1rem 0;
            background: linear-gradient(90deg, #6366f1 0%, #818cf8 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1.18rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 2px 12px rgba(99,102,241,0.13);
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 1px;
            z-index: 1;
            position: relative;
        }
        .login-card .btn:hover {
            background: #4f46e5;
            transform: translateY(-2px) scale(1.03);
        }
        .login-card .error {
            background: #fee2e2;
            color: #b91c1c;
            border-radius: 8px;
            padding: 0.8rem 1.1rem;
            margin-bottom: 1.2rem;
            font-size: 1.07rem;
            font-weight: 600;
            z-index: 1;
            position: relative;
        }
        .login-card p {
            margin-top: 1.7rem;
            font-size: 1.07rem;
            color: #a5b4fc;
            z-index: 1;
            position: relative;
        }
        .login-card a {
            color: #a5b4fc;
            text-decoration: underline;
            font-weight: 600;
            transition: color 0.2s;
        }
        .login-card a:hover {
            color: #fff;
        }
        @media (max-width: 600px) {
            .login-card {
                max-width: 98vw;
                padding: 1.2rem 0.5rem 1.2rem 0.5rem;
            }
            .login-card h1 {
                font-size: 1.4rem;
            }
            .login-card .btn {
                font-size: 1rem;
                padding: 0.8rem 0;
            }
        }
    </style>
</head>
<body>
<div class="login-card">
    <h1>Student Folder</h1>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>" required autofocus placeholder="Enter your username">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?= htmlspecialchars($password) ?>" required autocomplete="current-password" placeholder="Enter your password">
        <div class="remember-row">
            <input type="checkbox" name="remember" id="remember" <?= isset($_COOKIE['remembered_username']) && isset($_COOKIE['remembered_password']) ? 'checked' : '' ?>>
            <label for="remember" style="margin:0;cursor:pointer;">Remember me</label>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>