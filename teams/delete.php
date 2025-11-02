<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check role and permission
$role_id = $_SESSION['role_id'];
$is_superadmin = ($role_id == 1); // assuming 1 = super admin
$can_delete = $is_superadmin || has_permission($conn, $role_id, 'teams', 'delete');

if (!$can_delete) {
    die("❌ Access Denied: You do not have permission to delete team members.");
}

// Check if record exists and get photo
$stmt = $conn->prepare("SELECT photo FROM teams WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("⚠️ Team member not found.");
}

$row = $res->fetch_assoc();

// Delete photo
if (!empty($row['photo']) && file_exists("../uploads/teams/" . $row['photo'])) {
    @unlink("../uploads/teams/" . $row['photo']);
}

// Delete from DB
$stmt = $conn->prepare("DELETE FROM teams WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;
