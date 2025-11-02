<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'pending')) {
    die("❌ You do not have permission to mark pending.");
}

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid ID");

$conn->query("UPDATE news SET status='pending_editor', updated_at=NOW() WHERE id=$id");

header("Location: index.php?msg=pending");
exit;
?>
