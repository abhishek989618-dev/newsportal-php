<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Validate user session and role
$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

// Only allow if user has delete permission or is super admin
if (!$is_superadmin && !has_permission($conn, $role_id, 'scholarship', 'delete')) {
    die("❌ Access Denied: You don't have permission to delete scholarships.");
}

// Validate ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("❌ Invalid Scholarship ID.");
}

// Delete the record
$conn->query("DELETE FROM scholarship WHERE id = $id");

header("Location: index.php");
exit;
