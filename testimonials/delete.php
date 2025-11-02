<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Get user and role
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1; // assuming role_id 1 = super-admin

// Check delete permission
$can_delete = $is_superadmin || has_permission($conn, $role_id, 'testimonials', 'delete');
if (!$can_delete) {
    die("❌ Access Denied: You don't have permission to delete testimonials.");
}

// Get and validate ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("⚠️ Invalid ID");
}

// Fetch testimonial image
$stmt = $conn->prepare("SELECT image FROM testimonials WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("❌ Testimonial not found.");
}

$row = $res->fetch_assoc();

// Delete image file if exists
if (!empty($row['image'])) {
    $filePath = "../uploads/testimonials/" . $row['image'];
    if (file_exists($filePath)) {
        @unlink($filePath);
    }
}

// Delete testimonial
$stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;
