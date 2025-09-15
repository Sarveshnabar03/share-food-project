<?php
session_start();
require_once 'inc/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email == '' || $password == '') {
        $errors[] = "Please fill in both fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM ngos WHERE email = ?");
        $stmt->execute([$email]);
        $ngo = $stmt->fetch();

        if ($ngo && password_verify($password, $ngo['password'])) {
            $_SESSION['ngo_id'] = $ngo['id'];
            $_SESSION['ngo_name'] = $ngo['name'];
            $_SESSION['ngo_district'] = $ngo['district'];
            header("Location: ngo_dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<!-- HTML Form similar to user login -->
<!DOCTYPE html>
<html>
<head>
    <title>NGO Login</title>
    <style>
        /* Add your CSS from user login here */
    </style>
</head>
<body>
<div class="login-container">
    <h2>NGO Login</h2>

    <?php if(!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="link">
        <a href="ngo_register.php">Don’t have an account? Register</a>
    </div>
</div>
</body>
</html>
