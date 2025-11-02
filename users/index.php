<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'users', 'read')) {
    die("Access Denied.");
}

$can_update = has_permission($conn, $_SESSION['role_id'], 'users', 'update');
$can_delete = has_permission($conn, $_SESSION['role_id'], 'users', 'delete');

$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

// Get total user count
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM users");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch paginated users with role and block/verify status
$stmt = $conn->prepare("
    SELECT u.id, u.name, u.email, u.role_id, u.is_verified, u.is_blocked, u.last_active, r.role_name
FROM users u
LEFT JOIN roles r ON u.role_id = r.id
ORDER BY u.id DESC
LIMIT ?, ?

");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$res = $stmt->get_result();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-4">
    <div class="card shadow">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">👥 Users</h5>
        <span>Total Users: <strong><?= $total ?></strong></span>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Verified</th>
                <th>Blocked</th>
                <?php if ($can_update || $can_delete): ?>
                  <th>Actions</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
  <?php while ($u = $res->fetch_assoc()): 
    // Check if user is online (active within last 5 mins)
    $is_online = false;
    if (!empty($u['last_active'])) {
        $last_active_time = strtotime($u['last_active']);
        $is_online = (time() - $last_active_time) <= 300; // 5 minutes = 300 seconds
    }
  ?>
    <tr>
      <td>
        <?php if ($is_online): ?>
          <span class="ms-1" title="Online" style="color: #28a745;">●</span>
        <?php else: ?>
          <span class="ms-1" title="Offline" style="color: #dc3545;">●</span>
        <?php endif; ?>
        <?= htmlspecialchars($u['name']) ?>
        
      </td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['role_name'] ?? 'Not Assigned') ?></td>
      <td><?= $u['is_verified'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
      <td><?= $u['is_blocked'] ? '<span class="badge bg-danger">Yes</span>' : '<span class="badge bg-success">No</span>' ?></td>

      <?php if ($can_update || $can_delete): ?>
        <td>
          <?php if ($can_update): ?>
            <a href="edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary mb-1">Edit</a>
            <a href="assign_role.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-secondary mb-1">Assign Role</a>
            <?php if (!$u['is_verified']): ?>
              <a href="verify.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-success mb-1">Verify</a>
            <?php endif; ?>
            <a href="toggle_block.php?id=<?= $u['id'] ?>" 
               class="btn btn-sm <?= $u['is_blocked'] ? 'btn-success' : 'btn-warning' ?>">
              <?= $u['is_blocked'] ? 'Unblock' : 'Block' ?>
            </a>
          <?php endif; ?>

          <?php if ($can_delete): ?>
            <a href="delete.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Delete this user?')">Delete</a>
          <?php endif; ?>
        </td>
      <?php endif; ?>
    </tr>
  <?php endwhile; ?>
</tbody>

          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center">
          <div>
            Page <?= $page ?> of <?= $totalPages ?>
          </div>
          <div>
            <?php if ($page > 1): ?>
              <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-dark btn-sm">⬅ Prev</a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
              <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-dark btn-sm">Next ➡</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
