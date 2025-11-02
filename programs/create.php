 
<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'programs', 'create')) {
    die("❌ Access Denied.");
}

$websites = $conn->query("SELECT id, name FROM websites");
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0">🎓 Create Program</h5>
    </div>
    <div class="card-body">
      <form method="post" action="store.php" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Website(s)</label>
          <select name="website_ids[]" class="form-select" multiple>
            <?php while ($w = $websites->fetch_assoc()): ?>
              <option value="<?= $w['id'] ?>"><?= htmlspecialchars($w['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
         <div class="mb-3">
    <label class="form-label">Upload Image</label>
    <input type="file" name="image" class="form-control">
  </div>
        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">💾 Save Program</button>
      </form>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
