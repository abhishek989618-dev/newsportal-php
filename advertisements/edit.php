<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Permission check
$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

if (!$is_superadmin && !has_permission($conn, $role_id, 'advertisements', 'update')) {
    die("❌ Access Denied: You do not have permission to edit advertisements.");
}

// Validate and fetch ad by ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid advertisement ID.");
}

$stmt = $conn->prepare("SELECT * FROM advertisements WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$ad = $res->fetch_assoc();
if (!$ad) {
    die("Advertisement not found.");
}

$selected_websites = json_decode($ad['website_id'], true) ?? [];
$selected_positions = json_decode($ad['position_id'], true) ?? [];
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">🛠️ Edit Advertisement</h5>
      </div>

      <div class="card-body">
        <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" value="<?= htmlspecialchars($ad['title']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Target URL</label>
            <input name="link" class="form-control" value="<?= htmlspecialchars($ad['link']) ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Ad Type</label>
            <select name="ad_type" id="ad_type" class="form-select" required>
              <?php
              $types = ['image', 'gif', 'video', 'youtube', 'external'];
              foreach ($types as $type) {
                $sel = $ad['ad_type'] === $type ? 'selected' : '';
                echo "<option value='$type' $sel>" . ucfirst($type) . "</option>";
              }
              ?>
            </select>
          </div>

          <!-- Show current media -->
          <?php if (!empty($ad['media_path']) && in_array($ad['ad_type'], ['image', 'gif'])): ?>
            <div class="mb-3">
              <label class="form-label">Current Image</label><br>
              <img src="../uploads/ads/<?= htmlspecialchars($ad['media_path']) ?>" width="150" class="rounded shadow-sm mb-2">
            </div>
          <?php elseif (!empty($ad['media_path']) && $ad['ad_type'] === 'video'): ?>
            <div class="mb-3">
              <label class="form-label">Current Video</label><br>
              <video src="../uploads/ads/<?= htmlspecialchars($ad['media_path']) ?>" width="200" controls class="rounded shadow-sm mb-2"></video>
            </div>
          <?php endif; ?>

          <div class="mb-3" id="file_upload_group">
            <label class="form-label">Change File (optional)</label>
            <input type="file" name="file" class="form-control">
          </div>

          <div class="mb-3 d-none" id="youtube_url_group">
            <label class="form-label">YouTube URL</label>
            <input type="text" name="youtube_url" id="youtube_url" class="form-control" value="<?= htmlspecialchars($ad['youtube_url']) ?>" placeholder="https://youtube.com/watch?v=...">
          </div>

          <div class="mb-3 d-none" id="external_url_group">
            <label class="form-label">External Ad URL</label>
            <input type="text" name="external_url" id="external_url" class="form-control" value="<?= htmlspecialchars($ad['external_url']) ?>" placeholder="https://external.com...">
          </div>

          <div class="mb-3">
            <label class="form-label">Positions</label>
            <select name="position_ids[]" class="form-select" multiple>
              <?php
              $positions = $conn->query("SELECT * FROM positions");
              while ($position = $positions->fetch_assoc()) {
                $selected = in_array($position['id'], $selected_positions) ? 'selected' : '';
                echo "<option value='{$position['id']}' $selected>" . htmlspecialchars($position['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="active" <?= $ad['status'] === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="inactive" <?= $ad['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label">Select Website(s)</label>
            <select name="website_ids[]" multiple class="form-select" required>
              <?php
              $webs = $conn->query("SELECT id, name FROM websites");
              while ($w = $webs->fetch_assoc()) {
                  $sel = in_array($w['id'], $selected_websites) ? 'selected' : '';
                  echo "<option value='{$w['id']}' $sel>" . htmlspecialchars($w['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">💾 Update Advertisement</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleAdFields() {
    const adType = document.getElementById("ad_type").value;
    document.getElementById("file_upload_group").classList.add("d-none");
    document.getElementById("youtube_url_group").classList.add("d-none");
    document.getElementById("external_url_group").classList.add("d-none");

    if (["image", "gif", "video"].includes(adType)) {
      document.getElementById("file_upload_group").classList.remove("d-none");
    } else if (adType === "youtube") {
      document.getElementById("youtube_url_group").classList.remove("d-none");
    } else if (adType === "external") {
      document.getElementById("external_url_group").classList.remove("d-none");
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("ad_type").addEventListener("change", toggleAdFields);
    toggleAdFields();
  });
</script>

<?php include '../includes/footer.php'; ?>
