<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];
$id = (int)($_GET['id'] ?? 0);

// Role check
if ($role_id != 1 && !has_permission($conn, $role_id, 'scholarship', 'update')) {
    die("❌ Access Denied: You do not have permission to update scholarships.");
}

// Fetch scholarship
$res = $conn->query("SELECT * FROM scholarship WHERE id = $id");
if (!$res || $res->num_rows === 0) die("❌ Scholarship not found");
$data = $res->fetch_assoc();
$selected_websites = json_decode($data['website_id'], true) ?? [];
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">✏️ Edit Scholarship</h5>
      </div>
      <div class="card-body">
        <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Organization Name</label>
            <input type="text" name="organization_name" class="form-control"
              value="<?= htmlspecialchars($data['organization_name']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Scholarship Title</label>
            <input type="text" name="title" class="form-control"
              value="<?= htmlspecialchars($data['title']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($data['description']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Apply Link</label>
            <input type="url" name="apply_link" class="form-control"
              value="<?= htmlspecialchars($data['apply_link']) ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Upload New Logo</label>
            <input type="file" name="logo" class="form-control">
            <?php if (!empty($data['logo'])): ?>
              <div class="mt-2">
                <img src="../uploads/logos/<?= htmlspecialchars($data['logo']) ?>" width="100" class="img-thumbnail">
              </div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active" <?= $data['status'] == 'active' ? 'selected' : '' ?>>Active</option>
              <option value="inactive" <?= $data['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Select Website(s)</label>
            <select name="website_ids[]" class="form-select" multiple>
              <?php
              $webs = $conn->query("SELECT id, name FROM websites");
              while ($w = $webs->fetch_assoc()) {
                  $sel = in_array($w['id'], $selected_websites) ? 'selected' : '';
                  echo "<option value='{$w['id']}' $sel>" . htmlspecialchars($w['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">💾 Update Scholarship</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
