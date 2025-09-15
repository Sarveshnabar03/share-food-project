<?php
require_once __DIR__ . '/inc/db.php';

try {
    $stmt = $pdo->query("SELECT NOW() AS now");
    $row = $stmt->fetch();
    echo "✅ DB connection OK — server time: " . htmlspecialchars($row['now']);
} catch (Exception $e) {
    echo "❌ Test failed: " . htmlspecialchars($e->getMessage());
}
