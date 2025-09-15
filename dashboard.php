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

// Fetch all posts (food + cloth) from all users
$postStmt = $pdo->prepare("
    SELECT posts.*, users.name AS username 
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
");
$postStmt->execute();
$allPosts = $postStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch only my posts (food + cloth)
$myPostStmt = $pdo->prepare("
    SELECT * FROM posts
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$myPostStmt->execute([$_SESSION['user_id']]);
$myPosts = $myPostStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - ShareFood</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        }
        header {
            background: #4A90E2;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }
        .container {
            max-width: 1100px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
        }
        h2 {
            margin-top: 0;
            text-align: center;
            color: #333;
        }
        .links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }
        .links a {
            padding: 14px 24px;
            background: #4A90E2;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }
        .links a:hover {
            background: #357ABD;
        }
        .post-list {
            margin-top: 30px;
        }
        .post {
            border: 1px solid #e0e0e0;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            background: #fafafa;
            transition: transform 0.2s;
        }
        .post:hover {
            transform: translateY(-3px);
        }
        .post h3 {
            margin: 0;
            color: #222;
        }
        .post p {
            margin: 10px 0;
            line-height: 1.5;
        }
        .post small {
            color: #666;
        }
        .post img {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 6px;
        }
        footer {
            margin: 20px 0;
            text-align: center;
            font-size: 14px;
            color: #fff;
        }
        .type-label {
            font-size: 12px;
            background: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
        }

        /* Chatbot Button */
        #chatbot-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #4A90E2;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            z-index: 10000;
            transition: background 0.3s;
        }
        #chatbot-toggle:hover {
            background: #357ABD;
        }

        /* Chatbot Popup */
        #chatbot-container {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 320px;
            height: 420px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 12px;
            display: none; 
            flex-direction: column;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 10px rgba(0,0,0,0.25);
            overflow: hidden;
            z-index: 9999;
        }
        #chatbot-header {
            background: #4A90E2;
            color: white;
            padding: 12px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #chatbot-header button {
            background: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        #chatbot-messages {
            flex: 1;
            padding: 12px;
            overflow-y: auto;
            font-size: 14px;
        }
        #chatbot-input-area {
            display: flex;
            border-top: 1px solid #ddd;
        }
        #chatbot-input {
            flex: 1;
            border: none;
            padding: 10px;
            font-size: 14px;
        }
        #chatbot-input:focus { outline: none; }
        #chatbot-send {
            border: none;
            background: #4A90E2;
            color: white;
            padding: 10px;
            cursor: pointer;
        }
        #chatbot-send:hover {
            background: #357ABD;
        }
        .user-msg { color: #007bff; margin: 5px 0; }
        .bot-msg { color: #28a745; margin: 5px 0; }
    </style>
</head>
<body>

<header>
    Welcome, <?= htmlspecialchars($user['name']) ?> 👋
</header>

<div class="container">
    <h2>🌟 What would you like to do today?</h2>
    <div class="links">
        <a href="share_food.php">🍲 Share Food</a>
        <a href="share_cloth.php">👕 Share Clothes</a>
        <a href="nearby_ngos.php">🏢 Find Nearby NGOs</a>
        <a href="my_posts.php">📜 My Posts</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- My Posts -->
    <div class="post-list">
        <h2>📌 My Posts (Food & Clothes)</h2>
        <?php if (count($myPosts) > 0): ?>
            <?php foreach ($myPosts as $post): ?>
                <div class="post">
                    <h3>
                        <?= htmlspecialchars($post['title']) ?>
                        <span class="type-label"><?= ucfirst($post['type']) ?></span>
                    </h3>
                    <p><?= nl2br(htmlspecialchars($post['description'])) ?></p>
                    <small>City: <?= htmlspecialchars($post['city']) ?> | Pickup: <?= htmlspecialchars($post['pickup_time']) ?></small>
                    <?php if ($post['image']): ?>
                        <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have not posted any items yet.</p>
        <?php endif; ?>
    </div>

    <!-- All Posts -->
    <div class="post-list">
        <h2>📢 Latest Posts (All Users)</h2>
        <?php if (count($allPosts) > 0): ?>
            <?php foreach ($allPosts as $post): ?>
                <div class="post">
                    <h3>
                        <?= htmlspecialchars($post['title']) ?>
                        <span class="type-label"><?= ucfirst($post['type']) ?></span>
                    </h3>
                    <p><?= nl2br(htmlspecialchars($post['description'])) ?></p>
                    <small>
                        Posted by <?= htmlspecialchars($post['username']) ?> |
                        City: <?= htmlspecialchars($post['city']) ?> |
                        Pickup: <?= htmlspecialchars($post['pickup_time']) ?>
                    </small>
                    <?php if ($post['image']): ?>
                        <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts available yet.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> ShareFood | Helping Communities Together
</footer>

<!-- Chatbot Toggle Button -->
<button id="chatbot-toggle">💬</button>

<!-- Chatbot Popup -->
<div id="chatbot-container">
  <div id="chatbot-header">
    NGO Chatbot
    <button id="close-chat">✖</button>
  </div>
  <div id="chatbot-messages"></div>
  <div id="chatbot-input-area">
    <input type="text" id="chatbot-input" placeholder="Type your message...">
    <button id="chatbot-send">Send</button>
  </div>
</div>

<script>
const chatbotContainer = document.getElementById("chatbot-container");
const chatbotToggle = document.getElementById("chatbot-toggle");
const closeChat = document.getElementById("close-chat");

chatbotToggle.addEventListener("click", () => {
  chatbotContainer.style.display = "flex";
});
closeChat.addEventListener("click", () => {
  chatbotContainer.style.display = "none";
});

document.getElementById("chatbot-send").addEventListener("click", sendMessage);
document.getElementById("chatbot-input").addEventListener("keypress", function(e) {
  if (e.key === "Enter") sendMessage();
});

function sendMessage() {
  const input = document.getElementById("chatbot-input");
  const msg = input.value.trim();
  if (!msg) return;

  appendMessage("You", msg, "user-msg");
  input.value = "";

  fetch("chatbot.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ message: msg })
  })
  .then(res => res.json())
  .then(data => {
    appendMessage("Bot", data.reply, "bot-msg");
  })
  .catch(() => {
    appendMessage("Bot", "⚠️ Error: Could not connect to server.", "bot-msg");
  });
}

function appendMessage(sender, text, cls) {
  const box = document.getElementById("chatbot-messages");
  const div = document.createElement("div");
  div.className = cls;
  div.textContent = sender + ": " + text;
  box.appendChild(div);
  box.scrollTop = box.scrollHeight;
}
</script>

</body>
</html>
