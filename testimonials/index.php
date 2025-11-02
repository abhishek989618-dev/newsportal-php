<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

// Permissions
$can_create = $is_superadmin || has_permission($conn, $role_id, 'testimonials', 'create');
$can_read   = $is_superadmin || has_permission($conn, $role_id, 'testimonials', 'read');
$can_update = $is_superadmin || has_permission($conn, $role_id, 'testimonials', 'update');
$can_delete = $is_superadmin || has_permission($conn, $role_id, 'testimonials', 'delete');

if (!$can_read) die("❌ Access Denied: You do not have permission to view testimonials.");

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

$totalRes = $conn->query("SELECT COUNT(*) as total FROM testimonials");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

$stmt = $conn->prepare("SELECT * FROM testimonials ORDER BY id DESC LIMIT ?, ?");
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
        <h5 class="mb-0">💬 Testimonials</h5>
        <?php if ($can_create): ?>
          <a href="create.php" class="btn btn-sm btn-outline-light">+ Add Testimonial</a>
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
                  <th>Designation</th>
                  <th>Message</th>
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
                    <td><?= substr(strip_tags($row['message']), 0, 60) ?>...</td>
                    <?php if ($can_update || $can_delete): ?>
                      <td>
                        <?php if ($can_update): ?>
                          <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                        <?php endif; ?>
                        <?php if ($can_delete): ?>
                          <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this testimonial?')">🗑️ Delete</a>
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
          <div class="alert alert-warning">No testimonials found.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
