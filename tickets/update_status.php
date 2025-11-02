<?php
session_start();
require_once '../config.php';

$user_id = $_SESSION['user_id'] ?? null;
$role_id = $_SESSION['role_id'] ?? null;

$ticket_id = $_GET['id'] ?? null;
if (!$ticket_id || !$user_id) {
    http_response_code(403);
    echo "Invalid or unauthorized.";
    exit;
}

// Fetch ticket
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

if (!$ticket || (
    $ticket['user_id'] != $user_id &&
    $ticket['assigned_to'] != $user_id &&
    !in_array($role_id, [1, 2])
)) {
    http_response_code(403);
    echo "❌ You can't update this ticket.";
    exit;
}

// Status options
$valid_statuses = ['open', 'in_progress', 'resolved', 'closed'];
$success = false;
$error = null;

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'] ?? '';

    if (!in_array($new_status, $valid_statuses)) {
        $error = "Invalid status.";
    } else {
        $stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $ticket_id);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Ticket Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5">
    <h2>📌 Update Status - Ticket #<?= $ticket_id ?></h2>

    <?php if ($success): ?>
        <div class="alert alert-success">✅ Status updated successfully.</div>
        <a href="index.php" class="btn btn-primary">⬅️ Back to Tickets</a>
    <?php else: ?>
        <?php if ($error): ?>
            <div class="alert alert-danger">❗ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Status</label>
                <select name="status" class="form-select" required>
                    <?php foreach ($valid_statuses as $status): ?>
                        <option value="<?= $status ?>" <?= $ticket['status'] == $status ? 'selected' : '' ?>>
                            <?= ucfirst($status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">💾 Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
