<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Permissions
if (!has_permission($conn, $_SESSION['role_id'], 'devices', 'read')) {
    die("❌ Access Denied: You do not have permission to view devices.");
}
$can_create = has_permission($conn, $_SESSION['role_id'], 'devices', 'create');
$can_update = has_permission($conn, $_SESSION['role_id'], 'devices', 'update');
$can_delete = has_permission($conn, $_SESSION['role_id'], 'devices', 'delete');

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Total count
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM devices");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch paginated devices
$stmt = $conn->prepare("SELECT * FROM devices ORDER BY id DESC LIMIT ?, ?");
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
      <h5 class="mb-0">🏷️ devices</h5>
      <span>Total: <?= $total ?></span>
    </div>

    <div class="card-body">
      <?php if ($can_create): ?>
        <a href="create.php" class="btn btn-success btn-sm mb-3">+ Add device</a>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <?php if ($can_update || $can_delete): ?>
              <th>Actions</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php while ($device = $res->fetch_assoc()): ?>
              <tr>
                <td><?= $device['id'] ?></td>
                <td><?= htmlspecialchars($device['name']) ?></td>
                <?php if ($can_update || $can_delete): ?>
                <td>
                  <?php if ($can_update): ?>
                    <a href="edit.php?id=<?= $device['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                  <?php endif; ?>
                  <?php if ($can_delete): ?>
                    <a href="delete.php?id=<?= $device['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this device?')">Delete</a>
                  <?php endif; ?>
                </td>
                <?php endif; ?>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div>Page <?= $page ?> of <?= $totalPages ?></div>
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
<?php include '../includes/footer.php'?>
