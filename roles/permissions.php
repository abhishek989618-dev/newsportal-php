<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';
// ✅ Only allow Super Admin to set permissions
if ($_SESSION['role_id'] != 1) {
    die("❌ Access Denied: Only Super Admin can assign permissions.");
}

// ✅ Sanitize role ID
$role_id = intval($_GET['id'] ?? 0);
if (!$role_id) {
    die("Invalid role ID.");
}

// ✅ Fetch all available permissions
$permissions = $conn->query("SELECT * FROM permissions");

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM role_permissions WHERE role_id = $role_id");

    foreach ($_POST['perm'] as $perm_id) {
        $stmt = $conn->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $role_id, $perm_id);
        $stmt->execute();
    }

    echo "<p style='color:green;'>✅ Permissions updated!</p>";
}

// ✅ Get already assigned permissions
$assigned = [];
$res = $conn->query("SELECT permission_id FROM role_permissions WHERE role_id = $role_id");
while ($r = $res->fetch_assoc()) {
    $assigned[] = $r['permission_id'];
}
?>
<?php include '../includes/sidebar.php'; ?>
<style>
  .form-check-label {
    display: block;
    white-space: normal;      /* Allows text to wrap */
    word-wrap: break-word;    /* Break long words if needed */
    overflow-wrap: break-word;
  }
</style>


<div class="main">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
  <div class="card shadow">
    <div class="card-header brand-color text-white">
      <h5 class="mb-0">🔐 Set Permissions for Role #<?= $role_id ?></h5>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="row">
          <?php while ($perm = $permissions->fetch_assoc()): ?>
            <div class="col-md-6 mb-3">
              <div class="form-check border rounded px-3 py-2 bg-light">
                <input class="form-check-input" type="checkbox" name="perm[]" id="perm_<?= $perm['id'] ?>" value="<?= $perm['id'] ?>"
                  <?= in_array($perm['id'], $assigned) ? 'checked' : '' ?>>
                <label class="form-check-label" for="perm_<?= $perm['id'] ?>">
                  <strong><?= htmlspecialchars($perm['table_name']) ?></strong>
                  <span class="text-muted">(<?= htmlspecialchars($perm['operations']) ?>)</span>
                </label>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
        <div class="mt-3">
          <button type="submit" class="btn brand-color w-100"> Save Permissions</button>
        </div>
      </form>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>