<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];
$user_id = $_SESSION['user_id'];
$id = (int)($_GET['id'] ?? 0);

// 🔒 Check if user has delete permission for events
if ($role_id != 1 && !has_permission($conn, $role_id, 'events', 'delete')) {
    die("❌ Access Denied: You do not have permission to delete events.");
}

// Get the image before deleting
$res = $conn->prepare("SELECT image FROM events WHERE id = ?");
$res->bind_param("i", $id);
$res->execute();
$event = $res->get_result()->fetch_assoc();

if ($event && !empty($event['image'])) {
    $imagePath = "../uploads/events/" . $event['image'];
    if (file_exists($imagePath)) {
        @unlink($imagePath);
    }
}

// Delete the record
$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;
?>
