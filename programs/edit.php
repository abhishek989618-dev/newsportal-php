 
<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'programs', 'update')) {
    die("❌ Access Denied.");
}

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM programs WHERE id = $id");
$program = $res->fetch_assoc();
$selected_websites = json_decode($program['website_ids'] ?? '[]', true);

$websites = $conn->query("SELECT id, name FROM websites");
?>

<!-- UI -->
<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-dark text-white">✏️ Edit Program</div>
    <div class="card-body">
      <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input name="title" value="<?= htmlspecialchars($program['title']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control"><?= htmlspecialchars($program['description']) ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Websites</label>
          <select name="website_ids[]" class="form-select" multiple>
            <?php while ($w = $websites->fetch_assoc()): 
              $sel = in_array($w['id'], $selected_websites) ? 'selected' : '';
              echo "<option value='{$w['id']}' $sel>{$w['name']}</option>";
            endwhile; ?>
          </select>
        </div>
        <?php if (!empty($program['image'])): ?>
  <img src="../uploads/programs/<?= $program['image'] ?>" width="120" class="mb-2">
<?php endif; ?>
<div class="mb-3">
  <label class="form-label">Replace Image</label>
  <input type="file" name="image" class="form-control">
</div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active" <?= $program['status'] == 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $program['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
          </select>
        </div>
        <button class="btn btn-primary w-100">💾 Update</button>
      </form>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
