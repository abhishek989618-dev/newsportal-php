<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Permission check: delete on categories
if (!has_permission($conn, $_SESSION['role_id'], 'categories', 'delete')) {
    die("❌ Access Denied: You do not have permission to delete categories.");
}

// ✅ Sanitize and delete
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php");
exit;
