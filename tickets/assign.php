<?php
session_start();
require_once '../config.php'; // your DB connection here

// Only Admin or Super Admin
if (!in_array($_SESSION['role_id'], [1, 2])) {
    http_response_code(403);
    echo "❌ Access denied.";
    exit;
}

$ticket_id = $_GET['id'] ?? null;
$selected_role = $_POST['role_id'] ?? ($_GET['role_id'] ?? '');
$assigned_to = $_POST['assigned_to'] ?? null;
$success = false;
$error = null;

// Fetch ticket
if ($ticket_id) {
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $ticket = $stmt->get_result()->fetch_assoc();

    if (!$ticket) $error = "Ticket not found.";
} else {
    $error = "Missing ticket ID.";
}

// If role is selected, fetch users
$users = [];
if ($selected_role) {
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE role_id = ?");
    $stmt->bind_param("i", $selected_role);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $assigned_to && $ticket && !$error) {
    $stmt = $conn->prepare("UPDATE tickets SET assigned_to = ? WHERE id = ?");
    $stmt->bind_param("ii", $assigned_to, $ticket_id);
    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = "Database error: " . $conn->error;
    }
}

// Get all roles for selection
$rolesResult = $conn->query("SELECT id, role_name FROM roles WHERE id NOT IN (1, 2)");
$roles = $rolesResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5">
    <h2>👤 Assign Ticket #<?= htmlspecialchars($ticket_id) ?></h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <a href="index.php" class="btn btn-secondary">⬅️ Back</a>
    <?php elseif ($success): ?>
        <div class="alert alert-success">✅ Ticket assigned successfully.</div>
        <a href="index.php" class="btn btn-primary">⬅️ Back to Tickets</a>
    <?php elseif ($ticket): ?>
        <form method="POST" action="?id=<?= $ticket_id ?>">
            <div class="mb-3">
                <label class="form-label">Ticket Subject</label>
                <input type="text" class="form-control" disabled value="<?= htmlspecialchars($ticket['subject']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Select Role</label>
                <select name="role_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select Role --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= $selected_role == $role['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($role['role_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($selected_role && $users): ?>
                <div class="mb-3">
                    <label class="form-label">Select User</label>
                    <select name="assigned_to" class="form-select" required>
                        <option value="">-- Select User --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= $ticket['assigned_to'] == $user['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">✅ Assign Ticket</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            <?php elseif ($selected_role): ?>
                <div class="alert alert-info">ℹ️ No users found under this role.</div>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
