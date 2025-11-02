<?php
require '../session.php';
require '../config.php';

$id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

// First, validate user can access this notification
$stmt = $conn->prepare("
  SELECT id FROM notifications 
  WHERE id = ? 
    AND (
      user_id = ? 
      OR role_id = ? 
      OR JSON_CONTAINS(target_users, JSON_QUOTE(?), '$')
    )
");
$uid_str = (string)$user_id;
$stmt->bind_param("iiis", $id, $user_id, $role_id, $uid_str);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // User is a valid recipient, mark as read
    $update = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
    $update->bind_param("i", $id);
    $update->execute();
}

header("Location: index.php");
exit;
