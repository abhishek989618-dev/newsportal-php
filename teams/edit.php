<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];

// 🔐 Check update permission
if ($role_id != 1 && !has_permission($conn, $role_id, 'teams', 'update')) {
    die("❌ Access Denied: You do not have permission to edit team members.");
}

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM teams WHERE id = $id");
if (!$res || $res->num_rows === 0) {
    die("❌ Team member not found.");
}
$row = $res->fetch_assoc();
$selected_websites = json_decode($row['website_id'], true) ?? [];
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">✏️ Edit Team Member</h5>
      </div>
      <div class="card-body">
        <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" value="<?= htmlspecialchars($row['designation']) ?>" class="form-control">
          </div>

          <?php if (!empty($row['photo'])): ?>
            <div class="mb-3">
              <label class="form-label">Current Photo</label><br>
              <img src="../uploads/teams/<?= htmlspecialchars($row['photo']) ?>" width="100" class="rounded shadow-sm mb-2">
            </div>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label">Change Photo</label>
            <input type="file" name="photo" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active" <?= $row['status'] === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="inactive" <?= $row['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
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

          <button type="submit" class="btn btn-success w-100">💾 Update Member</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
