<?php
require '../session.php';
require '../config.php';

$ticket_id = (int)($_GET['ticket_id'] ?? 0);
if (!$ticket_id) exit;

$stmt = $conn->prepare("
    SELECT m.*, u.name AS sender_name
    FROM messages m 
    JOIN users u ON m.sender_id = u.id 
    WHERE m.ticket_id = ? 
    ORDER BY m.created_at ASC
");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$res = $stmt->get_result();

while ($msg = $res->fetch_assoc()) {
    echo "<p><strong>" . htmlspecialchars($msg['sender_name']) . "</strong>: " . 
         htmlspecialchars($msg['message']) . 
         " <small class='text-muted'>" . $msg['created_at'] . "</small></p>";
}
