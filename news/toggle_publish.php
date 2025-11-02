<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$id     = $_POST['id'] ?? 0;
$action = $_POST['action'] ?? '';

$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

$can_publish   = $is_superadmin || has_permission($conn, $role_id, 'news', 'publish');
$can_unpublish = $is_superadmin || has_permission($conn, $role_id, 'news', 'unpublish');

if (!$id || !$action || !in_array($action, ['publish', 'unpublish'])) {
    die("Invalid request.");
}

if (($action === 'publish' && !$can_publish) || ($action === 'unpublish' && !$can_unpublish)) {
    die("❌ Access Denied.");
}

$status = $action === 'publish' ? 'published' : 'verified'; // set to verified on unpublish
$publish_at = $action === 'publish' ? date("Y-m-d H:i:s") : null;

$stmt = $conn->prepare("UPDATE news SET status = ?, publish_at = ? WHERE id = ?");
$stmt->bind_param("ssi", $status, $publish_at, $id);
$stmt->execute();

header("Location: index.php");
exit;
