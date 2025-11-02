<?php
require '../session.php';
require '../config.php';

$id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

// Step 1: Fetch the current notification
$stmt = $conn->prepare("SELECT user_id, role_id, target_users FROM notifications WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Notification not found.");
}

$notification = $result->fetch_assoc();

// Step 2: Check access
$is_direct_user = $notification['user_id'] == $user_id;
$is_direct_role = $notification['role_id'] == $role_id;
$is_in_target_users = false;

$target_users = json_decode($notification['target_users'] ?? '[]', true);
if (is_array($target_users)) {
    $is_in_target_users = in_array($user_id, $target_users);
}

// Step 3: Decide action
if ($is_direct_user || $is_direct_role) {
    // Delete full notification if assigned directly
    $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
} elseif ($is_in_target_users) {
    // Remove user ID from target_users array
    $target_users = array_filter($target_users, function($uid) use ($user_id) {
        return $uid != $user_id;
    });
    $updated_json = json_encode(array_values($target_users)); // Reindex array

    $stmt = $conn->prepare("UPDATE notifications SET target_users = ? WHERE id = ?");
    $stmt->bind_param("si", $updated_json, $id);
    $stmt->execute();
}

header("Location: index.php");
exit;
