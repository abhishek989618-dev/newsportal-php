<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Permissions
if (!has_permission($conn, $_SESSION['role_id'], 'api_keys', 'read')) {
    die("❌ Access Denied: You do not have permission to view API keys.");
}
$can_create = has_permission($conn, $_SESSION['role_id'], 'api_keys', 'create');
$can_delete = has_permission($conn, $_SESSION['role_id'], 'api_keys', 'delete');

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Total entries
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM api_keys");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch paginated keys
$stmt = $conn->prepare("
    SELECT ak.id, ak.api_key, w.name AS website_name
    FROM api_keys ak
    JOIN websites w ON ak.website_id = w.id
    ORDER BY ak.id DESC
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
      <h5 class="mb-0">🔑 API Keys</h5>
      <span>Total: <?= $total ?></span>
    </div>

    <div class="card-body">
      <?php if ($can_create): ?>
        <a href="create.php" class="btn btn-success btn-sm mb-3">+ Generate API Key</a>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Website</th>
              <th>API Key</th>
              <?php if ($can_delete): ?>
              <th>Action</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $res->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['website_name']) ?></td>
                <td><code><?= $row['api_key'] ?></code></td>
                <?php if ($can_delete): ?>
                <td>
                  <a href="delete.php?id=<?= $row['id'] ?>"
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Delete this API key?')">Delete</a>
                </td>
                <?php endif; ?>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination controls -->
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