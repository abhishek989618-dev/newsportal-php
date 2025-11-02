<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];

// Allow Super Admin (1) or users with 'create' permission on 'teams'
if ($role_id != 1 && !has_permission($conn, $role_id, 'teams', 'create')) {
    die("❌ Access Denied: You do not have permission to add team members.");
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">👨‍💼 Add Team Member</h5>
      </div>
      <div class="card-body">
        <form method="post" action="store.php" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control" placeholder="Manager, Developer, etc.">
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Photo</label>
            <input type="file" name="photo" class="form-control">
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

          <button type="submit" class="btn btn-primary w-100">💾 Save Member</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
