<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Only allow Super Admin (role_id == 4)
if ($_SESSION['role_id'] != 1) {
    die("❌ Access Denied: Only Super Admin can manage roles.");
}

// ✅ Fetch all roles
$res = $conn->query("SELECT * FROM roles order by id asc");
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="card shadow border-dark">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">🛡️ Manage Roles</h5>
      <a href="create.php" class="btn btn-sm btn-outline-light">+ Add Role</a>
    </div>

    <div class="card-body">
      <?php if ($res->num_rows === 0): ?>
        <p class="text-muted">No roles found.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Role Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($role = $res->fetch_assoc()): ?>
                <tr>
                  <td><?= $role['id'] ?></td>
                  <td><?= htmlspecialchars($role['role_name']) ?></td>
                  <td>
                    <a href="permissions.php?id=<?= $role['id'] ?>" class="btn btn-sm btn-primary">Set Permissions</a>
                    <!-- <a href="delete.php?id=<?= $role['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this role?')">Delete</a> -->
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
