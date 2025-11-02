<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'reject')) {
    die("❌ You do not have permission to reject.");
}

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid ID");

$conn->query("UPDATE news SET status='rejected', updated_at=NOW() WHERE id=$id");

header("Location: index.php?msg=rejected");
exit;
?>
