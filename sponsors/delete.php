<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$role_id = $_SESSION['role_id'];
$is_superadmin = ($role_id == 1);

// ✅ Check permission before deletion
$can_delete = $is_superadmin || has_permission($conn, $role_id, 'sponsors', 'delete');

if (!$can_delete) {
    die("❌ Access Denied: You do not have permission to delete sponsors.");
}

// 🔒 Optional: Validate existence before deleting
$stmt = $conn->prepare("SELECT id FROM sponsors WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    die("❌ Sponsor not found.");
}

// 🚮 Perform the delete
$stmt = $conn->prepare("DELETE FROM sponsors WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;
