<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'accept')) {
    die("❌ You do not have permission to accept.");
}

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid ID");

$conn->query("UPDATE news SET status='verified', verified_by={$_SESSION['user_id']}, updated_at=NOW() WHERE id=$id");

header("Location: index.php?msg=accepted");
exit;
?>
