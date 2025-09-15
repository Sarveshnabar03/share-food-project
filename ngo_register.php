<?php
session_start();
require_once 'inc/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $district = trim($_POST['district']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if ($name === '' || $email === '' || $district === '' || $password === '' || $confirm === '') {
        $errors[] = "All required fields must be filled.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM ngos WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Email is already registered.";
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO ngos (name,email,phone,district,password) VALUES (?,?,?,?,?)");
        $stmt->execute([$name, $email, $phone, $district, $hashed]);

        $_SESSION['success'] = "Registration successful! You can login now.";
        header("Location: ngo_login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>NGO Register</title>
</head>
<body>
<div class="register-container">
    <h2>NGO Registration</h2>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Name*</label>
        <input type="text" name="name" required>

        <label>Email*</label>
        <input type="email" name="email" required>

        <label>Phone</label>
        <input type="text" name="phone">

        <label>District*</label>
        <select name="district" required>
            <option value="">-- Select District --</option>
            <option value="Ahmednagar">Ahmednagar</option>
            <option value="Pune">Pune</option>
            <option value="Mumbai City">Mumbai City</option>
            <!-- Add all other districts -->
        </select>

        <label>Password*</label>
        <input type="password" name="password" required>

        <label>Confirm Password*</label>
        <input type="password" name="confirm" required>

        <button type="submit">Register</button>
    </form>

    <div class="link">
        <a href="ngo_login.php">Already have an account? Login</a>
    </div>
</div>
</body>
</html>
