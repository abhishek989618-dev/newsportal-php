<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];
$id = (int)$_GET['id'] ?? 0;

// 🔐 Role-based update permission
if ($role_id != 1 && !has_permission($conn, $role_id, 'testimonials', 'update')) {
    die("❌ Access Denied: You do not have permission to update testimonials.");
}

// Fetch testimonial
$res = $conn->query("SELECT * FROM testimonials WHERE id = $id");
if (!$res || $res->num_rows == 0) {
    die("❌ Testimonial not found.");
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
      <h5 class="mb-0">✏️ Edit Testimonial</h5>
    </div>

    <div class="card-body">
      <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Designation</label>
          <input name="designation" class="form-control" value="<?= htmlspecialchars($row['designation']) ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Testimonial</label>
          <textarea name="message" class="form-control" rows="4"><?= htmlspecialchars($row['message']) ?></textarea>
        </div>

        <?php if ($row['image']): ?>
          <div class="mb-3">
            <label class="form-label">Current Photo</label><br>
            <img src="../uploads/testimonials/<?= htmlspecialchars($row['image']) ?>" width="100" class="rounded mb-2 shadow-sm">
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <label class="form-label">Replace Image</label>
          <input type="file" name="image" class="form-control">
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

        <button type="submit" class="btn btn-success w-100">💾 Update Testimonial</button>
      </form>
    </div>
  </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
