<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'users', 'delete')) {
    die("❌ Access Denied: You do not have permission to delete users.");
}

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: index.php");
