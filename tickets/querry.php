<?php
session_start();
require_once '../config.php';

$user_id = $_SESSION['user_id'] ?? null;
$role_id = $_SESSION['role_id'] ?? null;

if (!$user_id) {
    echo "❌ Unauthorized access.";
    exit;
}

// Hide manually
if (isset($_GET['hide']) && is_numeric($_GET['hide'])) {
    $hide_id = (int) $_GET['hide'];
    $stmt = $conn->prepare("INSERT IGNORE INTO ticket_hidden (ticket_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $hide_id, $user_id);
    $stmt->execute();
}

// Fetch assigned tickets not hidden or too old
$sql = "
    SELECT t.*, u.name AS created_by 
    FROM tickets t
    LEFT JOIN users u ON t.user_id = u.id
    WHERE t.assigned_to = ?
      AND (
        t.status NOT IN ('resolved', 'closed')
        OR (
            t.status IN ('resolved', 'closed')
            AND t.created_at >= NOW() - INTERVAL 2 DAY
        )
      )
      AND t.id NOT IN (
          SELECT ticket_id FROM ticket_hidden WHERE user_id = ?
      )
    ORDER BY t.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$tickets = $stmt->get_result();
?>

<!-- HTML output (similar to index.php) -->
<?php if (!in_array($role_id, [1, 2])): ?>
    <a href="?hide=<?= $ticket['id'] ?>" class="btn btn-sm btn-outline-danger">🫣 Hide</a>
<?php endif; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Assigned Queries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">
    <div class="container py-5">
        <h2>📌 Assigned Tickets</h2>

        <?php if ($tickets->num_rows > 0): ?>
            <table class="table table-dark table-bordered table-striped mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($ticket = $tickets->fetch_assoc()): ?>
                        <tr>
                            <td><?= $ticket['id'] ?></td>
                            <td><?= htmlspecialchars($ticket['subject']) ?></td>
                            <td>
                                <span class="badge bg-<?= match ($ticket['status']) {
                                                            'open' => 'warning',
                                                            'in_progress' => 'info',
                                                            default => 'light'
                                                        } ?>"><?= ucfirst($ticket['status']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($ticket['created_by']) ?></td>
                            <td><?= $ticket['created_at'] ?></td>
                            <td>
                                <a href="view.php?id=<?= $ticket['id'] ?>" class="btn btn-sm btn-info">👁 View</a>
                                <a href="update_status.php?id=<?= $ticket['id'] ?>" class="btn btn-sm btn-success">📌 Update Status</a>
                                <!-- HTML output (similar to index.php) -->
                                <?php if (!in_array($role_id, [1, 2])): ?>
                                    <a href="?hide=<?= $ticket['id'] ?>" class="btn btn-sm btn-outline-danger">🫣 Hide</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted mt-4">You have no open or in-progress assigned tickets.</p>
        <?php endif; ?>
    </div>
</body>

</html>