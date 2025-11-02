<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

// Permission check
if (!$is_superadmin && !has_permission($conn, $role_id, 'advertisements', 'create')) {
    die("❌ Access Denied: You do not have permission to create advertisements.");
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">📢 Add Advertisement</h5>
      </div>

      <div class="card-body">
        <form method="post" action="store.php" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input name="title" id="title" class="form-control" placeholder="Advertisement Title" required>
          </div>

          <div class="mb-3">
            <label for="link" class="form-label">Target URL</label>
            <input name="link" id="link" class="form-control" placeholder="https://example.com">
          </div>

          <div class="mb-3">
  <label class="form-label">Advertisement Type</label>
  <select name="ad_type" id="ad_type" class="form-select" required>
    <option value="image">🖼️ Image</option>
    <option value="gif">🎞️ GIF</option>
    <option value="video">📹 Video</option>
    <option value="youtube">▶️ YouTube</option>
    <option value="external">🌐 External Link</option>
  </select>
</div>


<!-- File Upload Field -->
<div class="mb-3" id="file_upload_group" style="display:none;">
  <label class="form-label">Upload File</label>
  <input type="file" name="file" id="file" class="form-control">
</div>

<!-- YouTube URL Field -->
<div class="mb-3" id="youtube_url_group" style="display:none;">
  <label class="form-label">YouTube URL</label>
  <input type="url" name="youtube_url" id="youtube_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
</div>

<!-- External URL Field -->
<div class="mb-3" id="external_url_group" style="display:none;">
  <label class="form-label">External Banner URL</label>
  <input type="url" name="external_url" id="external_url" class="form-control" placeholder="https://example.com/banner">
</div>


          <div class="mb-3">
            <label class="form-label">Positions (multiple)</label>
            <select name="position_ids[]" class="form-select" multiple>
              <?php
              $positions = $conn->query("SELECT * FROM positions");
              while ($position = $positions->fetch_assoc()) {
                echo "<option value='{$position['id']}'>" . htmlspecialchars($position['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

          <div class="mb-4">
            <label for="website_ids" class="form-label">Select Website(s)</label>
            <select name="website_ids[]" id="website_ids" class="form-select" multiple required>
              <?php
              $websites = $conn->query("SELECT id, name FROM websites");
              while ($w = $websites->fetch_assoc()) {
                  echo "<option value='{$w['id']}'>" . htmlspecialchars($w['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">💾 Save Advertisement</button>
        </form>
      </div>
    </div>
  </div>
<script>
  function toggleAdFields() {
    const adType = document.getElementById("ad_type").value;
    const fileGroup = document.getElementById("file_upload_group");
    const youtubeGroup = document.getElementById("youtube_url_group");
    const externalGroup = document.getElementById("external_url_group");

    // Hide all by default
    fileGroup.style.display = "none";
    youtubeGroup.style.display = "none";
    externalGroup.style.display = "none";

    if (["image", "gif", "video"].includes(adType)) {
      fileGroup.style.display = "block";
    } else if (adType === "youtube") {
      youtubeGroup.style.display = "block";
    } else if (adType === "external") {
      externalGroup.style.display = "block";
    }
  }

  // Initialize on DOM load
  document.addEventListener("DOMContentLoaded", function () {
    const adTypeSelect = document.getElementById("ad_type");
    adTypeSelect.addEventListener("change", toggleAdFields);
    toggleAdFields(); // Trigger initial state
  });
</script>


</div>
<?php include '../includes/footer.php'; ?>

