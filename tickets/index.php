<?php
session_start();
require_once '../config.php';

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

// Hide ticket manually
if (isset($_GET['hide']) && is_numeric($_GET['hide'])) {
  $hide_id = (int) $_GET['hide'];
  $stmt = $conn->prepare("INSERT IGNORE INTO ticket_hidden (ticket_id, user_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $hide_id, $user_id);
  $stmt->execute();
}

// Admins see all
if (in_array($role_id, [1, 2])) {
  $sql = "SELECT t.*, u.name AS created_by, a.name AS assigned_to_name
            FROM tickets t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN users a ON t.assigned_to = a.id
            ORDER BY t.created_at DESC";
  $tickets = $conn->query($sql);
} else {
  // Non-admin: fetch own tickets + show resolved/closed only if within 2 days + not hidden
  $sql = "
        SELECT t.*, u.name AS created_by, a.name AS assigned_to_name
        FROM tickets t
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN users a ON t.assigned_to = a.id
        WHERE t.user_id = ?
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
}
?>

<!-- The rest of your HTML and table output here -->
<!-- Add this Hide button to each row for non-admins -->

<?php if (!in_array($role_id, [1, 2])): ?>
  <a href="?hide=<?= $ticket['id'] ?>" class="btn btn-sm btn-outline-danger">🫣 Hide</a>
<?php endif; ?>

<!DOCTYPE html>
<html>

<head>
  <title>Tickets</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>🎫 Ticket List</h2>
      <a href="create.php" class="btn btn-primary">➕ Create Ticket</a>
    </div>

    <?php if ($tickets->num_rows > 0): ?>
      <table class="table table-dark table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Created By</th>
            <th>Assigned To</th>
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
                                        'resolved' => 'success',
                                        'closed' => 'secondary',
                                        default => 'light'
                                      } ?>"><?= ucfirst($ticket['status']) ?></span>
              </td>
              <td><?= htmlspecialchars($ticket['created_by'] ?? 'Unknown') ?></td>
              <td><?= htmlspecialchars($ticket['assigned_to_name'] ?? 'Unassigned') ?></td>
              <td><?= $ticket['created_at'] ?></td>
              <td>
                <a href="view.php?id=<?= $ticket['id'] ?>" class="btn btn-sm btn-info">👁 View</a>
                <?php if (in_array($role_id, [1, 2])): ?>
                  <a href="assign.php?id=<?= $ticket['id'] ?>" class="btn btn-sm btn-warning">👤 Assign</a>
                <?php endif; ?>
                <?php if ($ticket['user_id'] == $user_id || in_array($role_id, [1, 2])): ?>
                  <a href="update_status.php?id=<?= $ticket['id'] ?>" class="btn btn-sm btn-success">📌 Update Status</a>
                <?php endif; ?>
                <?php if (in_array($role_id, [1, 2])): ?>
                  <a href="delete.php?id=<?= $ticket['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this ticket?')">🗑 Delete</a>
                <?php endif; ?>
                <?php if (!in_array($role_id, [1, 2])): ?>
                  <a href="?hide=<?= $ticket['id'] ?>" class="btn btn-sm btn-outline-danger">🫣 Hide</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-muted">No tickets found.</p>
    <?php endif; ?>
  </div>
</body>

</html>