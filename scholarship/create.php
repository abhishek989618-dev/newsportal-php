<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Role check: allow only superadmin or those with 'create' permission on 'scholarship'
$role_id = $_SESSION['role_id'];
if ($role_id != 1 && !has_permission($conn, $role_id, 'scholarship', 'create')) {
    die("❌ Access Denied: You do not have permission to add scholarships.");
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">🎓 Add Scholarship</h5>
      </div>
      <div class="card-body">
        <form method="post" action="store.php" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Organization Name</label>
            <input type="text" name="organization_name" class="form-control" placeholder="e.g. UNDP" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Scholarship Title</label>
            <input type="text" name="title" class="form-control" placeholder="Scholarship Title" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Description..."></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Apply Link</label>
            <input type="url" name="apply_link" class="form-control" placeholder="https://apply.example.com" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Logo</label>
            <input type="file" name="logo" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Select Website(s)</label>
            <select name="website_ids[]" class="form-select" multiple>
              <?php
              $websites = $conn->query("SELECT id, name FROM websites");
              while ($w = $websites->fetch_assoc()) {
                  echo "<option value='{$w['id']}'>" . htmlspecialchars($w['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">💾 Save Scholarship</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
