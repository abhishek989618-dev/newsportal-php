<?php
require '../session.php';
require '../config.php';

$ticket_id = (int)($_POST['ticket_id'] ?? 0);
$message   = trim($_POST['message'] ?? '');
$sender_id = $_SESSION['user_id'];
$replied_by = $sender_id; // Assuming the replier is the logged-in user

if ($ticket_id && $message && $sender_id) {
    $stmt = $conn->prepare("
        INSERT INTO messages (ticket_id, sender_id, replied_by, message, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("iiis", $ticket_id, $sender_id, $replied_by, $message);
    $stmt->execute();
}
