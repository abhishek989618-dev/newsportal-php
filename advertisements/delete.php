<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

// Check permission: delete access to advertisements
if (!$is_superadmin && !has_permission($conn, $role_id, 'advertisements', 'delete')) {
    die("❌ Access Denied: You do not have permission to delete advertisements.");
}

// Optional: Prevent deletion of verified/published items if needed

// Fetch and delete image if exists
$res = $conn->query("SELECT media_path FROM advertisements WHERE id = $id");
if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    if (!empty($row['media_path']) && file_exists("../uploads/ads/" . $row['media_path'])) {
        unlink("../uploads/ads/" . $row['media_path']);
    }
}

// Delete the record
$conn->query("DELETE FROM advertisements WHERE id = $id");
header("Location: index.php");
exit;
