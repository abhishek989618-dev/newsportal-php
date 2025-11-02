 
<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];
$can_read = has_permission($conn, $role_id, 'programs', 'read');
$can_create = has_permission($conn, $role_id, 'programs', 'create');
$can_update = has_permission($conn, $role_id, 'programs', 'update');
$can_delete = has_permission($conn, $role_id, 'programs', 'delete');

if (!$can_read) die("❌ Access Denied");

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$totalRes = $conn->query("SELECT COUNT(*) as total FROM programs");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

$res = $conn->query("SELECT * FROM programs ORDER BY id DESC LIMIT $offset, $limit");
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main"><?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
      <h5>🎓 Programs</h5>
      <?php if ($can_create): ?>
        <a href="create.php" class="btn btn-light btn-sm">+ New Program</a>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <p>Total Entries: <strong><?= $total ?></strong></p>
      <table class="table table-bordered table-striped">
        <thead class="table-dark"><tr><th>ID</th><th>Title</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <?php while ($p = $res->fetch_assoc()): ?>
            <tr>
              <td><?= $p['id'] ?></td>
              <td><?= htmlspecialchars($p['title']) ?></td>
              <td><span class="badge bg-<?= $p['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($p['status']) ?></span></td>
              <td>
                <a href="view.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">View</a>
                <?php if ($can_update): ?>
                  <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="toggle_status.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-secondary">Toggle Status</a>
                <?php endif; ?>
                <?php if ($can_delete): ?>
                  <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete program?')">Delete</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <nav>
        <ul class="pagination">
          <?php if ($page > 1): ?><li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Prev</a></li><?php endif; ?>
          <?php if ($page < $totalPages): ?><li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li><?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
