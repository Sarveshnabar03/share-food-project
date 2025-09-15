<?php
require_once __DIR__ . '/inc/db.php';

try {
    $stmt = $pdo->query("SELECT id, name, email FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)) {
        echo "⚠️ No users found in the database.";
    } else {
        foreach ($users as $u) {
            echo $u['id'] . " - " . htmlspecialchars($u['name']) . " (" . htmlspecialchars($u['email']) . ")<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Error fetching users: " . htmlspecialchars($e->getMessage());
}
