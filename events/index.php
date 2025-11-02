<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Auth Info
$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

// Permissions
$can_create = $is_superadmin || has_permission($conn, $role_id, 'events', 'create');
$can_read   = $is_superadmin || has_permission($conn, $role_id, 'events', 'read');
$can_update = $is_superadmin || has_permission($conn, $role_id, 'events', 'update');
$can_delete = $is_superadmin || has_permission($conn, $role_id, 'events', 'delete');

// Block if no read access
if (!$can_read) {
    die("❌ Access Denied: You do not have permission to view events.");
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count total
$totalRes = $conn->query("SELECT COUNT(*) as total FROM events");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch events
$stmt = $conn->prepare("SELECT * FROM events ORDER BY event_date DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$res = $stmt->get_result();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">🎉 Events</h5>
        <?php if ($can_create): ?>
          <a href="create.php" class="btn btn-sm btn-outline-light">+ Add Event</a>
        <?php endif; ?>
      </div>

      <div class="card-body">
        <p>Total Entries: <strong><?= $total ?></strong></p>

        <?php if ($res->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Date</th>
                  <th>Location</th>
                  <th>Image</th>
                  <?php if ($can_update || $can_delete): ?>
                    <th>Actions</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php while ($event = $res->fetch_assoc()): ?>
                  <tr>
                    <td><?= $event['id'] ?></td>
                    <td><?= htmlspecialchars($event['title']) ?></td>
                    <td><?= htmlspecialchars($event['event_date']) ?></td>
                    <td><?= htmlspecialchars($event['location']) ?></td>
                    <td>
                      <?php if ($event['image']): ?>
                        <img src="../uploads/events/<?= htmlspecialchars($event['image']) ?>" width="70" class="rounded shadow-sm">
                      <?php else: ?>
                        <span class="text-muted">No Image</span>
                      <?php endif; ?>
                    </td>
                    <?php if ($can_update || $can_delete): ?>
                      <td>
                        <?php if ($can_update): ?>
                          <a href="edit.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                        <?php endif; ?>
                        <?php if ($can_delete): ?>
                          <a href="delete.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this event?')">🗑️ Delete</a>
                        <?php endif; ?>
                      </td>
                    <?php endif; ?>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-warning">No events found.</div>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div>Page <?= $page ?> of <?= $totalPages ?></div>
          <div>
            <?php if ($page > 1): ?>
              <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-secondary btn-sm">⬅ Prev</a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
              <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-secondary btn-sm">Next ➡</a>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
