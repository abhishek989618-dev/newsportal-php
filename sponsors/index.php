<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Permission setup
$role_id = $_SESSION['role_id'];
$is_superadmin = ($role_id == 1);

$can_create = $is_superadmin || has_permission($conn, $role_id, 'sponsors', 'create');
$can_read   = $is_superadmin || has_permission($conn, $role_id, 'sponsors', 'read');
$can_update = $is_superadmin || has_permission($conn, $role_id, 'sponsors', 'update');
$can_delete = $is_superadmin || has_permission($conn, $role_id, 'sponsors', 'delete');

if (!$can_read) {
    die("❌ Access Denied: You do not have permission to read sponsors.");
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$totalRes = $conn->query("SELECT COUNT(*) AS total FROM sponsors");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch paginated sponsors
$stmt = $conn->prepare("SELECT * FROM sponsors ORDER BY created_at DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$res = $stmt->get_result();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-4">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">🤝 Sponsors</h5>
        <?php if ($can_create): ?>
          <a href="create.php" class="btn btn-sm btn-outline-light">+ Add Sponsor</a>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <p>Total Entries: <strong><?= $total ?></strong></p>

        <?php if ($res->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Website</th>
                <th>Status</th>
                <th>Logo</th>
                <?php if ($can_update || $can_delete): ?>
                  <th>Actions</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php while ($s = $res->fetch_assoc()): ?>
              <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td>
                  <?php if (!empty($s['website'])): ?>
                    <a href="<?= htmlspecialchars($s['website']) ?>" target="_blank"><?= htmlspecialchars($s['website']) ?></a>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="badge <?= $s['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                    <?= ucfirst($s['status']) ?>
                  </span>
                </td>
                <td>
                  <?php if (!empty($s['logo'])): ?>
                    <img src="../uploads/sponsors/<?= htmlspecialchars($s['logo']) ?>" width="60" class="rounded">
                  <?php else: ?>
                    <span class="text-muted">No logo</span>
                  <?php endif; ?>
                </td>
                <?php if ($can_update || $can_delete): ?>
                <td>
                  <?php if ($can_update): ?>
                    <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                  <?php endif; ?>
                  <?php if ($can_delete): ?>
                    <a href="delete.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this sponsor?')">🗑️ Delete</a>
                  <?php endif; ?>
                </td>
                <?php endif; ?>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
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

        <?php else: ?>
          <div class="alert alert-warning">No sponsors found.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
