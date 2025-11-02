<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Permission checks
if (!has_permission($conn, $_SESSION['role_id'], 'websites', 'read')) {
    die("❌ Access Denied: You do not have permission to view websites.");
}

$can_create = has_permission($conn, $_SESSION['role_id'], 'websites', 'create');
$can_update = has_permission($conn, $_SESSION['role_id'], 'websites', 'update');
$can_delete = has_permission($conn, $_SESSION['role_id'], 'websites', 'delete');

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

// Count total
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM websites");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch paginated results
$stmt = $conn->prepare("SELECT * FROM websites ORDER BY id DESC LIMIT ?, ?");
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
      <h5 class="mb-0">🌐 Websites</h5>
      <span>Total: <?= $total ?></span>
    </div>
    <div class="card-body">

      <?php if ($can_create): ?>
        <a href="create.php" class="btn btn-success btn-sm mb-3">+ Add Website</a>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Domain</th>
              <th>Logo</th>
              <th>Email</th>
              <th>Status</th>
              <?php if ($can_update || $can_delete): ?>
              <th>Actions</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php while ($site = $res->fetch_assoc()): ?>
              <tr>
                <td><?= $site['id'] ?></td>
                <td><?= htmlspecialchars($site['name']) ?></td>
                <td>
                  <a href="<?= htmlspecialchars($site['domain']) ?>" target="_blank">
                    <?= htmlspecialchars($site['domain']) ?>
                  </a>
                </td>
                <td>
                  <?php if (!empty($site['logo'])): ?>
                    <img src="../uploads/logos/<?= htmlspecialchars($site['logo']) ?>" width="80" alt="Logo">
                  <?php else: ?>
                    <em>No logo</em>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($site['email']) ?></td>
                <td>
                  <span class="badge bg-<?= $site['status'] === 'active' ? 'success' : 'secondary' ?>">
                    <?= ucfirst($site['status']) ?>
                  </span>
                </td>
                <?php if ($can_update || $can_delete): ?>
                  <td>
                    <?php if ($can_update): ?>
                      <a href="edit.php?id=<?= $site['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <?php endif; ?>
                    <?php if ($can_delete): ?>
                      <a href="delete.php?id=<?= $site['id'] ?>" class="btn btn-sm btn-danger"
                         onclick="return confirm('Delete this website?')">Delete</a>
                    <?php endif; ?>
                    <a href="socials.php?id=<?= $site['id'] ?>" class="btn btn-sm btn-warning">Socials</a>
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
<?php include '../includes/footer.php'; ?>