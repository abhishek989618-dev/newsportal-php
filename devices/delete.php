<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Check permission
if (!has_permission($conn, $_SESSION['role_id'], 'devices', 'delete')) {
    die("❌ Access Denied: You do not have permission to delete devices.");
}

// ✅ Sanitize and validate ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Invalid device ID.");
}

// ✅ Execute deletion securely
$stmt = $conn->prepare("DELETE FROM devices WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;
