<?php
session_start();
require_once 'inc/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 1. Delete expired posts at the start of the script.
$now = date('Y-m-d H:i:s');
$stmt = $pdo->prepare("DELETE FROM posts WHERE expires_at < ? AND type = 'cloth'");
$stmt->execute([$now]);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $city        = trim($_POST['city']);
    $pickup_time = $_POST['pickup_time'];
    $user_id     = $_SESSION['user_id'];

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif'];
        if (!in_array(strtolower($ext), $allowed)) {
            $errors[] = "Invalid image format. Only JPG, PNG, GIF allowed.";
        } else {
            $imageName = time() . "_" . rand(1000,9999) . "." . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imageName);
        }
    }

    if ($title == '' || $city == '') {
        $errors[] = "Title and City are required.";
    }

    if (empty($errors)) {
        // Use pickup_time as the expiration time.
        $expires_at = $pickup_time; 

        $stmt = $pdo->prepare("INSERT INTO posts (user_id, type, title, description, image, city, pickup_time, expires_at) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$user_id, 'cloth', $title, $description, $imageName, $city, $pickup_time, $expires_at]);
        header("Location: share_cloth.php?success=1");
        exit;
    }
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? AND type = 'cloth' ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Share Cloth - ShareFood</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        form {
            margin: 20px 0;
        }
        form input, form textarea, form button {
            width: 100%;
            margin: 8px 0;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        form button {
            background: #007bff;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        form button:hover {
            background: #0056b3;
        }
        .success {
            color: green;
            background: #e8f8ee;
            padding: 10px;
            border-left: 5px solid green;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            background: #fdeaea;
            padding: 10px;
            border-left: 5px solid red;
            margin-bottom: 15px;
        }
        ul.posts {
            list-style: none;
            padding: 0;
        }
        ul.posts li {
            background: #fafafa;
            border: 1px solid #ddd;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
        }
        ul.posts li img {
            margin-top: 10px;
            border-radius: 6px;
        }
        a.back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 6px;
            background: #28a745;
            color: #fff;
        }
        a.back-link:hover {
            background: #218838;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Share Cloth</h2>

    <?php if (isset($_GET['success'])): ?>
        <p class="success">Post added successfully!</p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Title*:</label>
        <input type="text" name="title">
        
        <label>Description:</label>
        <textarea name="description"></textarea>
        
        <label>City*:</label>
        <input type="text" name="city">
        
        <label>Pickup Time:</label>
        <input type="datetime-local" name="pickup_time">
        
        <label>Image:</label>
        <input type="file" name="image">
        
        <button type="submit">Post</button>
    </form>

    <h3>Your Cloth Posts</h3>
    <ul class="posts">
    <?php foreach ($posts as $p): ?>
        <li>
            <strong><?= htmlspecialchars($p['title']) ?></strong> - <?= htmlspecialchars($p['city']) ?>
            <p>Expires on: <?= htmlspecialchars($p['expires_at']) ?></p>
            <?php if ($p['image']): ?>
                <br><img src="uploads/<?= htmlspecialchars($p['image']) ?>" width="150">
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>

    <a class="back-link" href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>