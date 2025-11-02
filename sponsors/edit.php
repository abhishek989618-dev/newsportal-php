<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];

// Check permission to update sponsors
if ($role_id != 1 && !has_permission($conn, $role_id, 'sponsors', 'update')) {
    die("❌ Access Denied: You do not have permission to update sponsors.");
}

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM sponsors WHERE id = $id");
if (!$res || $res->num_rows === 0) {
    die("❌ Sponsor not found.");
}

$s = $res->fetch_assoc();
$selected_websites = json_decode($s['website_id'], true) ?? [];
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">✏️ Edit Sponsor</h5>
      </div>
      <div class="card-body">
        <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Sponsor Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($s['name']) ?>" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Website</label>
            <input type="url" name="website" value="<?= htmlspecialchars($s['website']) ?>" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Upload New Logo</label>
            <input type="file" name="logo" class="form-control">
            <?php if (!empty($s['logo'])): ?>
              <div class="mt-2">
                <img src="../uploads/sponsors/<?= htmlspecialchars($s['logo']) ?>" width="100" class="rounded border">
              </div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active" <?= $s['status'] === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="inactive" <?= $s['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
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

          <button type="submit" class="btn btn-success w-100">💾 Update Sponsor</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
