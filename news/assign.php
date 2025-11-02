<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'assign')) {
    die("❌ You do not have permission to assign.");
}

$id = (int) ($_GET['id'] ?? 0);
$user_id = (int) ($_POST['user_id'] ?? 0);
if ($id <= 0 || $user_id <= 0) die("Invalid input");

$stmt = $conn->prepare("UPDATE news SET approved_by_editor = ?, updated_at = NOW() WHERE id = ?");
$stmt->bind_param("ii", $user_id, $id);
$stmt->execute();

header("Location: index.php?msg=assigned");
exit;
?>
