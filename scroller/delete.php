<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];
$is_superadmin = ($role_id == 1);

// Only allow users with delete permission on scroller or super admin
if (!$is_superadmin && !has_permission($conn, $role_id, 'scroller', 'delete')) {
    die("❌ Access Denied: You don't have permission to delete scroller messages.");
}

// Validate ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("❌ Invalid scroller ID.");
}

// Delete record
$conn->query("DELETE FROM scroller WHERE id = $id");

header("Location: index.php");
exit;
