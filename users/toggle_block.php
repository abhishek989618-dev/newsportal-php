<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Permission check
if (!has_permission($conn, $_SESSION['role_id'], 'users', 'update')) {
    die("❌ Access Denied: You do not have permission to block/unblock users.");
}

// Get user ID
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("❌ Invalid user ID.");
}

// Fetch current status
$res = $conn->query("SELECT is_blocked FROM users WHERE id = $id");
if (!$res || $res->num_rows === 0) {
    die("❌ User not found.");
}

$user = $res->fetch_assoc();
$current_status = $user['is_blocked'];

// Toggle status
$new_status = $current_status ? 0 : 1;

$stmt = $conn->prepare("UPDATE users SET is_blocked = ? WHERE id = ?");
$stmt->bind_param("ii", $new_status, $id);
$stmt->execute();

header("Location: index.php");
exit;
