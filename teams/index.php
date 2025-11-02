<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

// Permissions
$can_create = $is_superadmin || has_permission($conn, $role_id, 'teams', 'create');
$can_update = $is_superadmin || has_permission($conn, $role_id, 'teams', 'update');
$can_delete = $is_superadmin || has_permission($conn, $role_id, 'teams', 'delete');
$can_read   = $is_superadmin || has_permission($conn, $role_id, 'teams', 'read');

if (!$can_read) {
    die("❌ Access Denied: You do not have permission to view team members.");
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count total
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM teams");
$totalRows = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch records
$stmt = $conn->prepare("SELECT * FROM teams ORDER BY id DESC LIMIT ?, ?");
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
        <h5 class="mb-0">👥 Team Members</h5>
        <?php if ($can_create): ?>
          <a href="create.php" class="btn btn-sm btn-outline-light">+ Add Member</a>
        <?php endif; ?>
      </div>

      <div class="card-body">
        <p>Total Members: <strong><?= $totalRows ?></strong></p>

        <?php if ($res->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Designation</th>
                  <th>Image</th>
                  <th>Status</th>
                  <?php if ($can_update || $can_delete): ?>
                    <th>Actions</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $res->fetch_assoc()): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['designation']) ?></td>
                    <td>
                      <?php if (!empty($row['image'])): ?>
                        <img src="../uploads/teams/<?= htmlspecialchars($row['image']) ?>" width="60" class="rounded shadow-sm">
                      <?php else: ?>
                        <span class="text-muted">No Image</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="badge <?= $row['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= ucfirst($row['status']) ?>
                      </span>
                    </td>
                    <?php if ($can_update || $can_delete): ?>
                      <td>
                        <?php if ($can_update): ?>
                          <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                        <?php endif; ?>
                        <?php if ($can_delete): ?>
                          <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this member?')">🗑️ Delete</a>
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
          <div class="alert alert-warning">No team members found.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
