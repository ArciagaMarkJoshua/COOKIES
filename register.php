<?php
session_start();
require 'db.php';

$username = '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($username && $password && $confirm) {
        if ($password !== $confirm) {
            $error = "Passwords do not match.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Username already exists.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)")->execute([$username, $hash]);
                $success = "Registration successful! <a href='login.php'>Login here</a>.";
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            max-width: 400px;
            width: 96vw;
            margin: 0 auto;
            background: rgba(255,255,255,0.97);
            border-radius: 22px;
            box-shadow: 0 8px 32px 0 rgba(99,102,241,0.13), 0 1.5px 8px 0 #818cf844;
            padding: 2.8rem 2.5rem 2.2rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .register-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 22px;
            background: linear-gradient(120deg, #38bdf8 0%, #818cf8 100%);
            opacity: 0.10;
            z-index: 0;
        }
        .register-card h1 {
            color: #2563eb;
            margin-bottom: 2rem;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            z-index: 1;
            position: relative;
            text-shadow: 0 2px 12px #818cf822;
        }
        .register-card label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2563eb;
            font-weight: 600;
            text-align: left;
            margin-top: 1.2rem;
            letter-spacing: 0.5px;
            z-index: 1;
            position: relative;
        }
        .register-card input[type="text"],
        .register-card input[type="password"] {
            width: 100%;
            padding: 1rem 1.1rem;
            margin-bottom: 0.2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.13rem;
            background: rgba(129,140,248,0.11);
            color: #181c2f;
            box-shadow: 0 2px 8px rgba(129,140,248,0.08);
            outline: none;
            transition: background 0.2s, box-shadow 0.2s, border 0.2s;
            z-index: 1;
            position: relative;
        }
        .register-card input[type="text"]:focus,
        .register-card input[type="password"]:focus {
            background: rgba(56,189,248,0.18);
            box-shadow: 0 0 0 2px #38bdf8cc;
            border: 1.5px solid #38bdf8;
        }
        .register-card input[type="text"]::placeholder,
        .register-card input[type="password"]::placeholder {
            color: #38bdf8;
            opacity: 1;
        }
        .register-card .btn {
            width: 100%;
            margin-top: 1.7rem;
            padding: 1rem 0;
            background: linear-gradient(90deg, #38bdf8 0%, #bae6fd 100%);
            color: #2563eb;
            border: none;
            border-radius: 12px;
            font-size: 1.18rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 2px 12px rgba(99,102,241,0.10);
            transition: background 0.2s, color 0.2s, transform 0.1s;
            letter-spacing: 1px;
            z-index: 1;
            position: relative;
        }
        .register-card .btn:hover {
            background: #2563eb;
            color: #fff;
            transform: translateY(-2px) scale(1.03);
        }
        .register-card .error {
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
        .register-card .success {
            background: #d1fae5;
            color: #065f46;
            border-radius: 8px;
            padding: 0.8rem 1.1rem;
            margin-bottom: 1.2rem;
            font-size: 1.07rem;
            font-weight: 600;
            z-index: 1;
            position: relative;
        }
        .register-card p {
            margin-top: 1.7rem;
            font-size: 1.07rem;
            color: #2563eb;
            z-index: 1;
            position: relative;
        }
        .register-card a {
            color: #2563eb;
            text-decoration: underline;
            font-weight: 600;
            transition: color 0.2s;
        }
        .register-card a:hover {
            color: #38bdf8;
        }
        @media (max-width: 600px) {
            .register-card {
                max-width: 98vw;
                padding: 1.2rem 0.5rem 1.2rem 0.5rem;
            }
            .register-card h1 {
                font-size: 1.4rem;
            }
            .register-card .btn {
                font-size: 1rem;
                padding: 0.8rem 0;
            }
        }
    </style>
</head>
<body>
<div class="register-card">
    <h1>Register</h1>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
    <form method="post" autocomplete="off">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required placeholder="Create a username">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required placeholder="Create a password">
        <label for="confirm">Confirm Password</label>
        <input type="password" name="confirm" id="confirm" required placeholder="Confirm your password">
        <button type="submit" class="btn">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>