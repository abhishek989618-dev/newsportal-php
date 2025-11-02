<?php
require '../session.php';
require '../config.php';
include '../check_permission.php';
$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM gallery WHERE id = $id");
$row = $res->fetch_assoc();

$images = json_decode($row['images'] ?? '[]', true);
$selected_websites = json_decode($row['website_id'], true) ?? [];
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-4">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">🖼️ Edit Gallery</h5>
      </div>

      <div class="card-body">
        <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">📌 Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">📝 Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($row['description']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">🖼️ Current Images</label><br>
            <?php foreach ($images as $img): ?>
              <img src="../uploads/gallery/<?= $img ?>" class="me-2 mb-2" style="width: 100px; height: auto; border-radius: 4px;">
            <?php endforeach; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">➕ Add More Images (optional)</label>
            <input type="file" name="images[]" class="form-control" multiple>
          </div>

          <div class="mb-3">
            <label class="form-label">📍 Status</label>
            <select name="status" class="form-select">
              <option value="active" <?= $row['status'] === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="inactive" <?= $row['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">🌐 Select Website(s)</label>
            <select name="website_ids[]" class="form-select" multiple required>
              <?php
              $webs = $conn->query("SELECT id, name FROM websites");
              while ($w = $webs->fetch_assoc()) {
                  $sel = in_array($w['id'], $selected_websites) ? 'selected' : '';
                  echo "<option value='{$w['id']}' $sel>{$w['name']}</option>";
              }
              ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">🔄 Update Gallery</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
