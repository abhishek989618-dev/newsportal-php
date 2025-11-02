<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Check permission
if (!has_permission($conn, $_SESSION['role_id'], 'websites', 'delete')) {
    die("❌ Access Denied: You do not have permission to delete websites.");
}

// ✅ Get website ID securely
$id = intval($_GET['id']); // Sanitization

// ✅ Optionally: check if website exists before deletion
$stmt = $conn->prepare("DELETE FROM websites WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;
