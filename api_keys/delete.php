<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Check permission to delete API keys
if (!has_permission($conn, $_SESSION['role_id'], 'api_keys', 'delete')) {
    die("❌ Access Denied: You do not have permission to delete API keys.");
}

// ✅ Sanitize ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Invalid API key ID.");
}

// ✅ Secure deletion
$stmt = $conn->prepare("DELETE FROM api_keys WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;
