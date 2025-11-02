<?php
require '../session.php';
require '../config.php';

$title        = $_POST['title'] ?? '';
$message      = $_POST['message'] ?? '';
$type         = $_POST['type'] ?? '';
$target_type  = $_POST['target_type'] ?? '';
$role_id      = $_POST['role_id'] ?? null;
$user_ids     = $_POST['user_ids'] ?? [];
$tag_ids         = $_POST['tag_ids'] ?? [];
$category_id  = $_POST['category'] ?? null;
$website_id   = $_POST['website'] ?? null;

// Convert tag_ids to JSON
$tag_ids_json = !empty($tag_ids) ? json_encode($tag_ids) : null;

// Validate required
if (empty($title) || empty($message)) {
    die("❌ Title and message are required.");
}

if ($target_type === 'all' && $_SESSION['role_id'] == 1) {
    // Superadmin sending to all users
    $user_query = $conn->query("SELECT id FROM users");
    $all_users = [];
    while ($row = $user_query->fetch_assoc()) {
        $all_users[] = $row['id'];
    }
    $targetusers_json = json_encode($all_users);

    $stmt = $conn->prepare("INSERT INTO notifications 
        (title, message, type, tag_ids, category_id, website_id, target_users, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssissis", $title, $message, $type, $tag_ids_json, $category_id, $website_id, $targetusers_json);
    $stmt->execute();

} elseif (!empty($user_ids)) {
    // Send to specific users
    $stmt = $conn->prepare("INSERT INTO notifications 
        (title, message, user_id, role_id, type, tag_ids, category_id, website_id, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    foreach ($user_ids as $uid) {
        $stmt->bind_param("ssiiissi", $title, $message, $uid, $role_id, $type, $tag_ids_json, $category_id, $website_id);
        $stmt->execute();
    }

} elseif (!empty($role_id)) {
    // Send to whole role
    $null_user_id = null;
    $stmt = $conn->prepare("INSERT INTO notifications 
        (title, message, user_id, role_id, type, tag_ids, category_id, website_id, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param("ssiiissi", $title, $message, $null_user_id, $role_id, $type, $tag_ids_json, $category_id, $website_id);
    $stmt->execute();
}

header("Location: index.php");
exit;
