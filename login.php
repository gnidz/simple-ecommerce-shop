<?php
session_start();
require 'db_pdo.php';

$error = '';
$success = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = MD5(?)");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Set session and redirect to account page
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        header("Location: account.php");
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['reg_username'];
    $password = $_POST['reg_password'];
    $email = $_POST['reg_email'];

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $error = 'Username already exists.';
    } else {
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, MD5(?), ?, 'user')");
        if ($stmt->execute([$username, $password, $email])) {
            $success = 'Account created successfully! You can now log in.';
        } else {
            $error = 'Failed to create account. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In or Register - Litle Orchid Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .brand-hero {
            text-align: center;
            color: #fff;
            padding: 80px 20px 60px;
        }
        .brand-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .brand-hero p {
            font-size: 1.2rem;
            font-weight: 300;
            margin-bottom: 0;
        }
        .auth-section {
            margin-top: -40px;
            margin-bottom: 60px;
        }
        .auth-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .auth-card h2 {
            font-size: 1.75rem;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .auth-card .form-label {
            font-weight: 500;
        }
        .auth-card input.form-control {
            border-radius: 20px;
        }
        .auth-card button.btn {
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .auth-card button.btn:hover {
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Brand Hero Section -->
    <div class="brand-hero">
        <h1>Little Orchid Shop</h1>
        <p>Welcome to a better experience</p>
    </div>

    <div class="container auth-section">
        <?php if ($error): ?>
            <div class="alert alert-danger text-center mx-auto mb-4" style="max-width: 600px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success text-center mx-auto mb-4" style="max-width: 600px;"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="row justify-content-center g-5">
            
            <!-- Login Card -->
            <div class="col-md-5">
                <div class="auth-card">
                    <h2>Sign In</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required placeholder="Enter your username">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100 py-2 mt-3">Login</button>
                    </form>
                </div>
            </div>

            <!-- Register Card -->
            <div class="col-md-5">
                <div class="auth-card">
                    <h2>Create Account</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="reg_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="reg_username" name="reg_username" required placeholder="Choose a username">
                        </div>
                        <div class="mb-3">
                            <label for="reg_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="reg_email" name="reg_email" required placeholder="you@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="reg_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="reg_password" name="reg_password" required placeholder="Create a strong password">
                        </div>
                        <button type="submit" name="register" class="btn btn-success w-100 py-2 mt-3">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
