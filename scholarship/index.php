<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

$can_create = $is_superadmin || has_permission($conn, $role_id, 'scholarship', 'create');
$can_read   = $is_superadmin || has_permission($conn, $role_id, 'scholarship', 'read');
$can_update = $is_superadmin || has_permission($conn, $role_id, 'scholarship', 'update');
$can_delete = $is_superadmin || has_permission($conn, $role_id, 'scholarship', 'delete');

if (!$can_read) die("❌ Access Denied.");

$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Total entries
$totalRes = $conn->query("SELECT COUNT(*) as total FROM scholarship");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Paginated records
$stmt = $conn->prepare("SELECT * FROM scholarship ORDER BY created_at DESC LIMIT ?, ?");
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
        <h5 class="mb-0">🎓 Scholarships</h5>
        <?php if ($can_create): ?>
          <a href="create.php" class="btn btn-sm btn-outline-light">+ Add Scholarship</a>
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
                  <th>Organization</th>
                  <th>Apply Link</th>
                  <th>Status</th>
                  <?php if ($can_update || $can_delete): ?>
                    <th>Actions</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php while ($s = $res->fetch_assoc()): ?>
                  <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= htmlspecialchars($s['title']) ?></td>
                    <td><?= htmlspecialchars($s['organization_name']) ?></td>
                    <td>
                      <a href="<?= htmlspecialchars($s['apply_link']) ?>" target="_blank" class="btn btn-sm btn-info">Apply</a>
                    </td>
                    <td>
                      <span class="badge <?= $s['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= ucfirst($s['status']) ?>
                      </span>
                    </td>
                    <?php if ($can_update || $can_delete): ?>
                      <td>
                        <?php if ($can_update): ?>
                          <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                        <?php endif; ?>
                        <?php if ($can_delete): ?>
                          <a href="delete.php?id=<?= $s['id'] ?>" onclick="return confirm('Delete this scholarship?')" class="btn btn-sm btn-danger">🗑️ Delete</a>
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
          <div class="alert alert-warning">No scholarships found.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
