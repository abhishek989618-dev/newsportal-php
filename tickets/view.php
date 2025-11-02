<?php
session_start();
require_once '../config.php';

$user_id = $_SESSION['user_id'] ?? null;
$role_id = $_SESSION['role_id'] ?? null;

$ticket_id = $_GET['id'] ?? null;
if (!$ticket_id || !$user_id) {
    http_response_code(403);
    echo "Unauthorized access.";
    exit;
}

// Fetch ticket info
$stmt = $conn->prepare("SELECT t.*, 
                               u.name AS created_by, 
                               a.name AS assigned_to_name 
                        FROM tickets t
                        LEFT JOIN users u ON t.user_id = u.id
                        LEFT JOIN users a ON t.assigned_to = a.id
                        WHERE t.id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

// Access check
if (!$ticket || (
    $ticket['user_id'] != $user_id &&
    $ticket['assigned_to'] != $user_id &&
    !in_array($role_id, [1, 2])
)) {
    http_response_code(403);
    echo "❌ You are not allowed to view this ticket.";
    exit;
}

// Reply logic
$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'])) {
    $reply = trim($_POST['reply']);
    if (empty($reply)) {
        $errors[] = "Reply cannot be empty.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO ticket_messages (ticket_id, sender_id, reply) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $ticket_id, $user_id, $reply);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}

// Fetch all replies
$stmt = $conn->prepare("SELECT m.*, u.name AS sender_name 
                        FROM ticket_messages m
                        JOIN users u ON m.sender_id = u.id
                        WHERE m.ticket_id = ?
                        ORDER BY m.sent_at ASC");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5">
    <h2>📄 Ticket #<?= htmlspecialchars($ticket['id']) ?> - <?= htmlspecialchars($ticket['subject']) ?></h2>

    <div class="mb-4">
        <p><strong>Status:</strong> <?= ucfirst($ticket['status']) ?></p>
        <p><strong>Created By:</strong> <?= htmlspecialchars($ticket['created_by']) ?></p>
        <p><strong>Assigned To:</strong> <?= htmlspecialchars($ticket['assigned_to_name'] ?? 'Unassigned') ?></p>
        <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($ticket['message'])) ?></p>
        <?php if ($ticket['attachment']): ?>
            <p><strong>Attachment:</strong>
                <a href="../uploads/tickets/<?= $ticket['attachment'] ?>" class="btn btn-sm btn-outline-light" target="_blank">📎 View</a>
            </p>
        <?php endif; ?>
        <p><small><strong>Created At:</strong> <?= $ticket['created_at'] ?></small></p>
    </div>

    <h4 class="mt-5">🗨️ Replies</h4>
    <?php if ($messages->num_rows > 0): ?>
        <div class="mb-4">
            <?php while ($msg = $messages->fetch_assoc()): ?>
                <div class="border p-3 mb-3 rounded bg-secondary">
                    <p class="mb-1"><strong><?= htmlspecialchars($msg['sender_name']) ?>:</strong></p>
                    <p><?= nl2br(htmlspecialchars($msg['reply'])) ?></p>
                    <small class="text-light"><?= $msg['sent_at'] ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">No replies yet.</p>
    <?php endif; ?>

    <?php
    $canReply = (
        $ticket['user_id'] == $user_id ||
        $ticket['assigned_to'] == $user_id ||
        in_array($role_id, [1, 2])
    );
    ?>

    <?php if ($canReply): ?>
        <h4>✍️ Add Reply</h4>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ Reply sent.</div>
        <?php elseif ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $e): ?>
                    <p><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <textarea name="reply" class="form-control" rows="4" placeholder="Write your reply here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-success">💬 Submit Reply</button>
            <a href="index.php" class="btn btn-secondary">⬅️ Back</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
