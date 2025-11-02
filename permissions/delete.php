<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Only Super Admin can delete
if ($_SESSION['role_id'] != 1) {
    die("❌ Access Denied.");
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid ID.");
}

// Delete the permission
$stmt = $conn->prepare("DELETE FROM permissions WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?deleted=1");
    exit;
} else {
    die("❌ Failed to delete.");
}
?>
