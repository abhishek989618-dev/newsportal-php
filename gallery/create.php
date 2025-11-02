<?php
require '../session.php';
require '../config.php';
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-4">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">🖼️ Add New Gallery</h5>
      </div>
      <div class="card-body">
        <form method="post" action="store.php" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">📌 Title</label>
            <input type="text" name="title" class="form-control" placeholder="Gallery Title" required>
          </div>

          <div class="mb-3">
            <label class="form-label">📝 Description</label>
            <textarea name="description" class="form-control" placeholder="Gallery Description"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">🖼️ Upload Images</label>
            <input type="file" name="images[]" class="form-control" multiple required>
            <div class="form-text">You can select multiple images</div>
          </div>

          <div class="mb-3">
            <label class="form-label">📍 Status</label>
            <select name="status" class="form-select">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">🌐 Select Website(s)</label>
            <select name="website_ids[]" class="form-select" multiple required>
              <?php
              $websites = $conn->query("SELECT id, name FROM websites");
              while ($w = $websites->fetch_assoc()) {
                  echo "<option value='{$w['id']}'>{$w['name']}</option>";
              }
              ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">💾 Save Gallery</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
