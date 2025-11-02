<?php
session_start();
require_once '../config.php';

$user_id = $_SESSION['user_id'] ?? null;
$role_id = $_SESSION['role_id'] ?? null;

// Only Admins and Super Admins allowed
if (!in_array($role_id, [1, 2])) {
    http_response_code(403);
    echo "❌ Access denied. Only admins can delete tickets.";
    exit;
}

// Get ticket ID from URL
$ticket_id = $_GET['id'] ?? null;
if (!$ticket_id) {
    echo "❗ No ticket ID provided.";
    exit;
}

// Fetch ticket to check existence and attachment
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    echo "❗ Ticket not found.";
    exit;
}

// Delete attachment if it exists
if (!empty($ticket['attachment'])) {
    $filePath = '../uploads/tickets/' . $ticket['attachment'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// Delete ticket
$stmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");
$stmt->bind_param("i", $ticket_id);

if ($stmt->execute()) {
    header("Location: index.php?deleted=1");
    exit;
} else {
    echo "❌ Failed to delete ticket: " . $conn->error;
}
?>
