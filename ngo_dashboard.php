<?php
session_start();
require_once 'inc/db.php';

if (!isset($_SESSION['ngo_id'])) {
    header("Location: ngo_login.php");
    exit;
}

// Fetch posts from users in the same district
$district = $_SESSION['ngo_district'] ?? '';

$stmt = $pdo->prepare("
    SELECT posts.*, users.name AS user_name 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    WHERE users.city = ? 
    ORDER BY posts.created_at DESC
");
$stmt->execute([$district]);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>NGO Dashboard</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['ngo_name']); ?></h1>
    <p>Posts from your district: <?= htmlspecialchars($district); ?></p>

    <a href="ngo_logout.php">Logout</a>

    <hr>

    <?php if ($posts): ?>
        <?php foreach ($posts as $post): ?>
            <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <h3><?= htmlspecialchars($post['title']); ?></h3>
                <p><?= htmlspecialchars($post['description']); ?></p>
                <p><strong>Type:</strong> <?= htmlspecialchars($post['type']); ?></p>
                <p><strong>Posted by:</strong> <?= htmlspecialchars($post['user_name']); ?></p>
                <p><strong>Pickup Time:</strong> <?= htmlspecialchars($post['pickup_time']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts from your district yet.</p>
    <?php endif; ?>
</body>
</html>
