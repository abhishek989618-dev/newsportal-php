<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];

// 🔐 Permission check
if ($role_id != 1 && !has_permission($conn, $role_id, 'testimonials', 'create')) {
    die("❌ Access Denied: You do not have permission to add testimonials.");
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">📝 Add Testimonial</h5>
      </div>
      <div class="card-body">
        <form method="post" action="store.php" enctype="multipart/form-data">
          
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" class="form-control" placeholder="Name" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Designation</label>
            <input name="designation" class="form-control" placeholder="CEO, Developer etc.">
          </div>

          <div class="mb-3">
            <label class="form-label">Testimonial</label>
            <textarea name="message" class="form-control" rows="4" placeholder="Enter testimonial here..." required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Photo</label>
            <input type="file" name="image" class="form-control">
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
            <select name="website_ids[]" multiple class="form-select">
              <?php
              $websites = $conn->query("SELECT id, name FROM websites");
              while ($w = $websites->fetch_assoc()) {
                  echo "<option value='{$w['id']}'>" . htmlspecialchars($w['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">💾 Save Testimonial</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
