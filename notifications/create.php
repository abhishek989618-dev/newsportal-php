<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if ($_SESSION['role_id'] != 1) die("❌ Access Denied"); // Only admin

$selected_role = $_POST['role_id'] ?? '';
$title = $_POST['title'] ?? '';
$message = $_POST['message'] ?? '';
$category = $_POST['category'] ?? '';
$website = $_POST['website'] ?? '';
$type = $_POST['type'] ?? '';
$selected_user_ids = $_POST['user_ids'] ?? [];
$selected_tags = $_POST['tags'] ?? [];

// Fetch roles, tags, categories, websites
$roles = $conn->query("SELECT id, role_name FROM roles");
$tags = $conn->query("SELECT id, name FROM tags");
$categories = $conn->query("SELECT id, name FROM categories");
$websites = $conn->query("SELECT id, name FROM websites");

$users = [];
if ($selected_role) {
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE role_id = ?");
    $stmt->bind_param("i", $selected_role);
    $stmt->execute();
    $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="card">
    <div class="card-header bg-dark text-white">📢 Create Notification</div>
    <div class="card-body">
      <form method="post" action="">
        <div class="mb-3">
          <label>Title</label>
          <input name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
        </div>

        <div class="mb-3">
          <label>Message</label>
          <textarea name="message" class="form-control" required><?= htmlspecialchars($message) ?></textarea>
        </div>

        <div class="mb-3">
          <label>Select Type</label>
          <select name="type" class="form-select" required>
            <option value="">-- Select Type --</option>
            <option value="public" <?= $type == 'public' ? 'selected' : '' ?>>Public</option>
            <option value="dashboard" <?= $type == 'dashboard' ? 'selected' : '' ?>>Dashboard</option>
            <option value="private" <?= $type == 'private' ? 'selected' : '' ?>>Private</option>
          </select>
        </div>

        <div class="mb-3">
          <label>Select Role</label>
          <select name="role_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Select Role --</option>
            <?php while ($r = $roles->fetch_assoc()): ?>
              <option value="<?= $r['id'] ?>" <?= $selected_role == $r['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['role_name']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <?php if ($selected_role): ?>
          <div class="mb-3">
            <label>Select Users</label><br>
            <?php if (!empty($users)): ?>
              <?php foreach ($users as $user): ?>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="user_ids[]" value="<?= $user['id'] ?>"
                         <?= in_array($user['id'], $selected_user_ids) ? 'checked' : '' ?>>
                  <label class="form-check-label"><?= htmlspecialchars($user['name']) ?></label>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-muted">No users found for this role.</p>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <label>Select Tags</label><br>
          <?php while ($tag = $tags->fetch_assoc()): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="tags[]" value="<?= $tag['name'] ?>"
                     <?= in_array($tag['name'], $selected_tags) ? 'checked' : '' ?>>
              <label class="form-check-label"><?= htmlspecialchars($tag['name']) ?></label>
            </div>
          <?php endwhile; ?>
        </div>

        <div class="mb-3">
          <label>Select Category</label>
          <!-- Category -->
<select name="category" class="form-select">
  <option value="">-- Select Category --</option>
  <?php while ($cat = $categories->fetch_assoc()): ?>
    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category ? 'selected' : '' ?>>
      <?= htmlspecialchars($cat['name']) ?>
    </option>
  <?php endwhile; ?>
</select>
        </div>

        <div class="mb-3">
          <label>Select Website</label>
          <select name="website" class="form-select">
  <option value="">-- Select Website --</option>
  <?php while ($web = $websites->fetch_assoc()): ?>
    <option value="<?= $web['id'] ?>" <?= $web['id'] == $website ? 'selected' : '' ?>>
      <?= htmlspecialchars($web['name']) ?>
    </option>
  <?php endwhile; ?>
</select>
        </div>
        <!-- Notification Target -->
<div class="mb-3">
  <label>Send To:</label>
  <select name="target_type" class="form-select" required>
    <option value="">Select</option>
    <option value="user">Selected Users</option>
    <option value="role">Role</option>
    <option value="all">All Users (Super Admin Only)</option>
  </select>
</div>


        <button type="submit" formaction="store.php" class="btn btn-primary">Send</button>
      </form>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
