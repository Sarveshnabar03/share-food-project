<?php
session_start();
require_once 'inc/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $userId = $_SESSION['user_id'];

    // File details
    $fileName = $_FILES['photo']['name'];
    $fileTmp  = $_FILES['photo']['tmp_name'];
    $fileSize = $_FILES['photo']['size'];
    $fileError = $_FILES['photo']['error'];

    // Allowed file types
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileError === 0) {
        if (in_array($ext, $allowed)) {
            if ($fileSize < 2 * 1024 * 1024) { // 2MB limit
                // Unique file name
                $newFileName = "user_" . $userId . "_" . time() . "." . $ext;
                $dest = "uploads/" . $newFileName;

                // Move file
                if (move_uploaded_file($fileTmp, $dest)) {
                    // Save filename in DB
                    $stmt = $pdo->prepare("UPDATE users SET photo = ? WHERE id = ?");
                    $stmt->execute([$newFileName, $userId]);

                    $message = "Profile photo updated successfully!";
                } else {
                    $message = "Error moving file.";
                }
            } else {
                $message = "File is too large. Max 2MB.";
            }
        } else {
            $message = "Invalid file type. Only JPG, PNG, GIF allowed.";
        }
    } else {
        $message = "Upload error.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Profile Photo</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h2 { margin-top: 0; }
        input[type="file"] { margin: 10px 0; }
        button { padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
        .msg { margin-top: 10px; color: #333; }
        a { display: inline-block; margin-top: 15px; color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <h2>Upload Profile Photo</h2>
    <?php if ($message): ?>
        <p class="msg"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="photo" required>
        <br>
        <button type="submit">Upload</button>
    </form>
    <a href="my_posts.php">⬅ Back to My Posts</a>
</div>
</body>
</html>
