<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'inc/db.php';

// Fetch logged-in user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle post deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
    $postId = (int)$_POST['delete_post_id'];

    // Check if the post belongs to the logged-in user
    $checkStmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
    $checkStmt->execute([$postId, $_SESSION['user_id']]);
    $postToDelete = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($postToDelete) {
        // Delete post image if exists
        if ($postToDelete['image'] && file_exists("uploads/" . $postToDelete['image'])) {
            unlink("uploads/" . $postToDelete['image']);
        }

        // Delete post from database
        $delStmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $delStmt->execute([$postId]);

        // Redirect to refresh page
        header("Location: my_posts.php");
        exit;
    } else {
        $error = "Post not found or you are not authorized to delete it.";
    }
}

// Fetch only my posts
$postStmt = $pdo->prepare("
    SELECT * FROM posts
    WHERE user_id = ? AND type = 'food'
    ORDER BY created_at DESC
");
$postStmt->execute([$_SESSION['user_id']]);
$myPosts = $postStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Posts - ShareFood</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        header { background: #28a745; color: white; padding: 15px; text-align: center; }
        .container { max-width: 900px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; }
        h2 { text-align: center; margin-top: 0; }
        .profile { display: flex; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 15px; }
        .profile img { width: 80px; height: 80px; border-radius: 50%; margin-right: 15px; object-fit: cover; }
        .profile-info { font-size: 14px; }
        .post { border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-bottom: 15px; background: #fafafa; }
        .post img { max-width: 200px; display: block; margin-top: 10px; border-radius: 5px; }
        a.btn, button.btn { display: inline-block; padding: 8px 15px; margin-top: 10px; color: white; text-decoration: none; border-radius: 5px; cursor: pointer; border: none; }
        a.btn { background: #007bff; }
        a.btn:hover { background: #0056b3; }
        button.btn { background: #dc3545; }
        button.btn:hover { background: #a71d2a; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<header>
    <h1>My Food Posts - <?= htmlspecialchars($user['name']) ?></h1>
</header>

<div class="container">

    <!-- User Profile Section -->
    <div class="profile">
        <img src="uploads/<?= $user['photo'] ? htmlspecialchars($user['photo']) : 'default.png' ?>" alt="User Photo">
        <div class="profile-info">
            <strong><?= htmlspecialchars($user['name']) ?></strong><br>
            Email: <?= htmlspecialchars($user['email']) ?><br>
            City: <?= htmlspecialchars($user['city'] ?? 'Not set') ?><br>
            <a href="upload_photo.php">Change Profile Photo</a>
        </div>
    </div>

    <!-- Error Message -->
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <h2>Your Posts</h2>

    <?php if (count($myPosts) > 0): ?>
        <?php foreach ($myPosts as $post): ?>
            <div class="post">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($post['description'])) ?></p>
                <small>City: <?= htmlspecialchars($post['city']) ?> | Pickup: <?= htmlspecialchars($post['pickup_time']) ?></small>
                <?php if ($post['image']): ?>
                    <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
                <?php endif; ?>

                <!-- Delete Button -->
                <form method="POST" action="">
                    <input type="hidden" name="delete_post_id" value="<?= $post['id'] ?>">
                    <button type="submit" class="btn">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have not posted any food items yet.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>
