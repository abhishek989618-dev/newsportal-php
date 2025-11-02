<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'update')) {
  die("Permission denied.");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Safely fetch news using prepared statement
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$news = $res->fetch_assoc();

if (!$news) {
  die("News not found.");
}

// Decode arrays with fallback
$points_array = json_decode($news['points'] ?? '[]', true) ?: [];
$selected_category = [$news['category_id']];
$selected_type = [$news['type_id']];
$selected_websites = json_decode($news['website_id'] ?? '[]', true) ?: [];
$selected_tags = json_decode($news['tag_id'] ?? '[]', true) ?: [];
$selected_devices = json_decode($news['device_id'] ?? '[]', true) ?: [];
$selected_positions = json_decode($news['position_id'] ?? '[]', true) ?: [];
$media_files = json_decode($news['media'] ?? '[]', true) ?: [];
?>

<?php include '../includes/sidebar.php' ?>
<!-- Main Content -->
<div class="main">
  <?php include '../includes/navbar.php'; ?>
  <div class="container mt-4">
    <div class="card shadow-lg bg-dark text-light border-light">
      <div class="card-header border-light">
        <h3>🛠️ Edit News</h3>
      </div>
      <div class="card-body">
        <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" value="<?= htmlspecialchars($news['title']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Select Category</label>
            <select name="category_id" class="form-select">
              <?php
              $cats = $conn->query("SELECT id, name FROM categories");
              while ($cat = $cats->fetch_assoc()) {
                $sel = in_array($cat['id'], $selected_category) ? 'selected' : '';
                echo "<option value='{$cat['id']}' $sel>" . htmlspecialchars($cat['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Select Type</label>
            <select name="type_id" class="form-select">
              <?php
              $tys = $conn->query("SELECT id, name FROM types");
              while ($ty = $tys->fetch_assoc()) {
                $sel = in_array($ty['id'], $selected_type) ? 'selected' : '';
                echo "<option value='{$ty['id']}' $sel>" . htmlspecialchars($ty['name']) . "</option>";
              }
              ?>
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

          <div class="mb-3">
            <label class="form-label">Select Tags</label>
            <select name="tag_ids[]" class="form-select" multiple>
              <?php
              $tags = $conn->query("SELECT id, name FROM tags");
              while ($t = $tags->fetch_assoc()) {
                $sel = in_array($t['id'], $selected_tags) ? 'selected' : '';
                echo "<option value='{$t['id']}' $sel>" . htmlspecialchars($t['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Select Devices</label>
            <select name="device_ids[]" class="form-select" multiple>
              <?php
              $devices = $conn->query("SELECT id, name FROM devices");
              while ($dev = $devices->fetch_assoc()) {
                $sel = in_array($dev['id'], $selected_devices) ? 'selected' : '';
                echo "<option value='{$dev['id']}' $sel>" . htmlspecialchars($dev['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Select Positions</label>
            <select name="position_ids[]" class="form-select" multiple>
              <?php
              $positions = $conn->query("SELECT id, name FROM positions");
              while ($pos = $positions->fetch_assoc()) {
                $sel = in_array($pos['id'], $selected_positions) ? 'selected' : '';
                echo "<option value='{$pos['id']}' $sel>" . htmlspecialchars($pos['name']) . "</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Media (images, videos, GIFs)</label>
            <input type="file" name="media[]" class="form-control" multiple>

            <?php if (!empty($media_files)): ?>
              <div class="mt-3 d-flex flex-wrap gap-3">
                <?php foreach ($media_files as $file): ?>
                  <?php
                  if (is_array($file)) {
                    // Skip or convert properly if needed
                    continue;
                  }

                  $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                  $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                  $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                  $isYouTube = preg_match('/youtu\.be|youtube\.com/', $file);
                  ?>
                  <div class="position-relative text-center">
                    <?php if ($isImage): ?>
                      <img src="../uploads/news_media/<?= htmlspecialchars($file) ?>" width="100" class="rounded border">
                    <?php elseif ($isVideo): ?>
                      <video width="100" controls>
                        <source src="../uploads/news_media/<?= htmlspecialchars($file) ?>">
                      </video>
                    <?php elseif ($isYouTube): ?>
                      <iframe width="100" height="56" src="<?= htmlspecialchars($file) ?>" frameborder="0" allowfullscreen></iframe>
                    <?php endif; ?>
                    <input type="hidden" name="existing_media[]" value="<?= htmlspecialchars($file) ?>">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="removeImage(this)">✖</button>
                  </div>
                <?php endforeach; ?>

              </div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5"><?= htmlspecialchars($news['content']) ?></textarea>
          </div>
          <!-- location start -->
          <div class="mb-3">
            <label class="form-label">Location Name</label>
            <div class="input-group">
              <input name="location" id="location" class="form-control" value="<?= htmlspecialchars($news['location']) ?>" placeholder="Type location (e.g., New York, Eiffel Tower)">
              <button type="button" class="btn btn-outline-secondary" onclick="getLocation()">📍 Use My Location</button>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Latitude</label>
              <input type="text" name="latitude" id="latitude" class="form-control" value="<?= htmlspecialchars($news['latitude'] ?? '') ?>" placeholder="Latitude">
            </div>
            <div class="col-md-6">
              <label class="form-label">Longitude</label>
              <input type="text" name="longitude" id="longitude" class="form-control" value="<?= htmlspecialchars($news['longitude'] ?? '') ?>" placeholder="Longitude">
            </div>
          </div>
          <!-- location end-->
          <div class="mb-3">
            <label class="form-label">Highlights</label>
            <textarea name="highlights" class="form-control" rows="2"><?= htmlspecialchars($news['highlights']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Points</label>
            <div id="points-wrapper">
              <?php foreach ($points_array as $point): ?>
                <div class="input-group mb-2 point-field">
                  <input type="text" name="points[]" class="form-control" value="<?= htmlspecialchars($point) ?>" placeholder="Bullet point">
                  <button type="button" class="btn btn-outline-danger" onclick="removePoint(this)">🗑️</button>
                </div>
              <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-outline-light btn-sm" onclick="addPoint()">+ Add Point</button>
          </div>

          <div class="mb-3">
            <label class="form-label">News Date</label>
            <input name="news_date" type="date" class="form-control" value="<?= htmlspecialchars($news['news_date']) ?>">
          </div>

          <div class="mb-4">
            <label class="form-label">Internal Notes</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($news['notes']) ?></textarea>
          </div>

          <button class="btn btn-primary w-100">🚀 Update News</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  async function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(async function(position) {
          const lat = position.coords.latitude;
          const lon = position.coords.longitude;

          document.getElementById('latitude').value = lat;
          document.getElementById('longitude').value = lon;

          // Reverse geocode to get human-readable location
          try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`);
            const data = await res.json();
            document.getElementById('location').value = data.display_name || '';
          } catch (e) {
            console.warn('Reverse geocoding failed:', e);
          }
        },
        function(error) {
          alert("Failed to get location: " + error.message);
        });
    } else {
      alert("Geolocation not supported in this browser.");
    }
  }

  // Forward geocoding: location name ➝ lat/lon
  async function geocodeLocationName() {
    const locationInput = document.getElementById('location').value.trim();
    if (locationInput.length < 3) return;

    try {
      const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(locationInput)}`);
      const data = await res.json();

      if (data && data.length > 0) {
        const result = data[0];
        document.getElementById('latitude').value = result.lat;
        document.getElementById('longitude').value = result.lon;
      }
    } catch (e) {
      console.warn('Geocoding failed:', e);
    }
  }

  // Listen for changes on location input
  document.addEventListener('DOMContentLoaded', () => {
    const locationField = document.getElementById('location');
    let typingTimer;

    locationField.addEventListener('input', () => {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(geocodeLocationName, 800); // wait for user to stop typing
    });
  });
</script>

<script>
  function addPoint() {
    const wrapper = document.getElementById('points-wrapper');
    const div = document.createElement('div');
    div.className = 'input-group mb-2 point-field';
    div.innerHTML = `
    <input type="text" name="points[]" class="form-control" placeholder="Bullet point">
    <button type="button" class="btn btn-outline-danger" onclick="removePoint(this)">🗑️</button>
  `;
    wrapper.appendChild(div);
  }

  function removePoint(button) {
    button.closest('.point-field')?.remove();
  }

  function removeImage(button) {
    const container = button.closest('div');
    if (container) {
      container.remove();
    }
  }
</script>

<?php include '../includes/footer.php'; ?>