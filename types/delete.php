<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Permission check: delete on types
if (!has_permission($conn, $_SESSION['role_id'], 'types', 'delete')) {
    die("❌ Access Denied: You do not have permission to delete types.");
}

// ✅ Sanitize and delete
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM types WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php");
exit;
