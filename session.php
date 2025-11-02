<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: /news-portal/auth/login.php");
    exit;
}

$user_name = $_SESSION['user_name'] ?? 'User';
$user_id = $_SESSION['user_id'];

// Include DB connection
require_once __DIR__ . '/config.php';

// ✅ Update last_active once every 2 minutes
if (!isset($_SESSION['last_activity_update']) || time() - $_SESSION['last_activity_update'] > 120) {
    $_SESSION['last_activity_update'] = time();

    // Prepared statement for safety
    $stmt = $conn->prepare("UPDATE users SET last_active = NOW() WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}
?>
