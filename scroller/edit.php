<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];

// Check update permission for scroller
if ($role_id != 1 && !has_permission($conn, $role_id, 'scroller', 'update')) {
    die("❌ Access Denied: You do not have permission to edit scroller messages.");
}

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM scroller WHERE id = $id");
if (!$res || $res->num_rows === 0) {
    die("❌ Scroller message not found.");
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
        <h5 class="mb-0">✏️ Edit Scroller Message</h5>
      </div>
      <div class="card-body">
        <form method="post" action="update.php?id=<?= $id ?>">

          <div class="mb-3">
            <label class="form-label">Scrolling Text</label>
            <input type="text" name="text" class="form-control" value="<?= htmlspecialchars($row['text']) ?>" required>
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
            <select name="website_ids[]" multiple class="form-select">
              <?php
              $webs = $conn->query("SELECT id, name FROM websites");
              while ($w = $webs->fetch_assoc()) {
                  $sel = in_array($w['id'], $selected_websites) ? 'selected' : '';
                  echo "<option value='{$w['id']}' $sel>" . htmlspecialchars($w['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <button type="submit" class="btn btn-success w-100">🔄 Update Message</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
