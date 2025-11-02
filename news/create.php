<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'create')) {
  die("Permission denied.");
}
?>
<?php include '../includes/sidebar.php' ?>
<!-- Main Content -->
<div class="main">
  <?php include '../includes/navbar.php'; ?>
  <div class="container mt-4">
    <div class="card shadow-lg bg-dark text-light border-light">
      <div class="card-header border-light">
        <h3 class="mb-0">🚀 Create News</h3>
      </div>
      <div class="card-body">
        <form method="post" action="store.php" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required placeholder="Enter news title">
          </div>

          <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5" placeholder="Enter full content"></textarea>
          </div>

          <!-- Location Autocomplete and Map -->
          <div class="mb-3 position-relative">
            <label class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" placeholder="Enter location">
            <div id="location-suggestions" class="autocomplete-suggestions"></div>
          </div>

          <input type="hidden" name="latitude" id="latitude">
          <input type="hidden" name="longitude" id="longitude">

          <div id="map" class="mb-4" style="height: 300px;"></div>


          <div class="mb-3">
            <label class="form-label">Highlights</label>
            <textarea name="highlights" class="form-control" rows="2" placeholder="Key points summary"></textarea>
          </div>

          <!-- 🔥 Points -->
          <div class="mb-3">
            <label class="form-label">Points</label>
            <div id="points-wrapper">
              <div class="input-group mb-2 point-field">
                <input type="text" name="points[]" class="form-control" placeholder="Bullet point">
                <button class="btn btn-outline-danger" type="button" onclick="removePoint(this)">🗑️</button>
              </div>
            </div>
            <button type="button" onclick="addPoint()" class="btn btn-sm btn-outline-light">+ Add Point</button>
          </div>

          <div class="mb-3">
            <label class="form-label">News Date</label>
            <input type="date" name="news_date" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select">
              <?php
              $cats = $conn->query("SELECT * FROM categories");
              while ($cat = $cats->fetch_assoc()) {
                echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type_id" class="form-select">
              <?php
              $tys = $conn->query("SELECT * FROM types");
              while ($ty = $tys->fetch_assoc()) {
                echo "<option value='{$ty['id']}'>{$ty['name']}</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Websites (multiple)</label>
            <select name="website_ids[]" class="form-select" multiple>
              <?php
              $webs = $conn->query("SELECT * FROM websites");
              while ($w = $webs->fetch_assoc()) {
                echo "<option value='{$w['id']}'>{$w['name']}</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tags (multiple)</label>
            <select name="tag_ids[]" class="form-select" multiple>
              <?php
              $tags = $conn->query("SELECT * FROM tags");
              while ($tag = $tags->fetch_assoc()) {
                echo "<option value='{$tag['id']}'>{$tag['name']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Positions (multiple)</label>
            <select name="position_ids[]" class="form-select" multiple>
              <?php
              $positions = $conn->query("SELECT * FROM positions");
              while ($position = $positions->fetch_assoc()) {
                echo "<option value='{$position['id']}'>{$position['name']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Devices (multiple)</label>
            <select name="device_ids[]" class="form-select" multiple>
              <?php
              $devices = $conn->query("SELECT * FROM devices");
              while ($device = $devices->fetch_assoc()) {
                echo "<option value='{$device['id']}'>{$device['name']}</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Internal Notes</label>
            <textarea name="notes" class="form-control" placeholder="Optional internal notes"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Media Type</label>
            <select id="mediaType" class="form-select" onchange="toggleMediaInputs()">
              <option value="image">Image(s)</option>
              <option value="video">Video</option>
              <option value="gif">GIF</option>
              <option value="youtube">YouTube Link</option>
            </select>
          </div>

          <div class="mb-3 media-input" id="media-image">
            <label class="form-label">Upload Image(s)</label>
            <input type="file" name="images[]" class="form-control" multiple>
          </div>

          <div class="mb-3 media-input d-none" id="media-video">
            <label class="form-label">Upload Video</label>
            <input type="file" name="video" class="form-control" accept="video/*">
          </div>

          <div class="mb-3 media-input d-none" id="media-gif">
            <label class="form-label">Upload GIF</label>
            <input type="file" name="gif" class="form-control" accept="image/gif">
          </div>

          <div class="mb-3 media-input d-none" id="media-youtube">
            <label class="form-label">YouTube Video URL</label>
            <input type="url" name="youtube_link" class="form-control" placeholder="https://youtube.com/watch?v=...">
          </div>


          <button class="btn btn-primary w-100">🚀 Submit News</button>
        </form>
      </div>
    </div>
  </div>

</div>
<!-- 📍 Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<style>
  .autocomplete-suggestions {
    position: absolute;
    background: white;
    color: black;
    border: 1px solid #ccc;
    z-index: 999;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
  }
  .autocomplete-suggestion {
    padding: 5px 10px;
    cursor: pointer;
  }
  
</style>

<script>
  let map = L.map('map').setView([20.5937, 78.9629], 5);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
  let locationMarker;

  function setupLocationAutocomplete() {
    const input = document.getElementById("location");
    const suggestionsBox = document.getElementById("location-suggestions");

    input.addEventListener("input", () => {
      let query = input.value;
      if (query.length < 3) return suggestionsBox.innerHTML = '';
      fetch(`https://photon.komoot.io/api/?q=${query}&limit=5`)
        .then(res => res.json())
        .then(data => {
          suggestionsBox.innerHTML = '';
          data.features.forEach(place => {
            const label = `${place.properties.name || ''}, ${place.properties.city || ''}, ${place.properties.country || ''}`;
            const div = document.createElement("div");
            div.classList.add("autocomplete-suggestion");
            div.textContent = label;
            div.onclick = () => {
              input.value = label;
              suggestionsBox.innerHTML = '';
              const [lon, lat] = place.geometry.coordinates;
              document.getElementById("latitude").value = lat;
              document.getElementById("longitude").value = lon;
              if (locationMarker) map.removeLayer(locationMarker);
              locationMarker = L.marker([lat, lon]).addTo(map).bindPopup("Selected Location").openPopup();
              map.setView([lat, lon], 12);
            };
            suggestionsBox.appendChild(div);
          });
        });
    });
  }

  function toggleMediaInputs() {
    const selected = document.getElementById("mediaType").value;
    document.querySelectorAll('.media-input').forEach(div => div.classList.add('d-none'));
    document.getElementById('media-' + selected)?.classList.remove('d-none');
  }

  document.addEventListener("DOMContentLoaded", () => {
    setupLocationAutocomplete();
    toggleMediaInputs();
  });
</script>


<!-- ✅ JavaScript for Dynamic Points -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    window.addPoint = function() {
      const wrapper = document.getElementById('points-wrapper');
      const div = document.createElement('div');
      div.className = 'input-group mb-2 point-field';
      div.innerHTML = `
      <input type="text" name="points[]" class="form-control" placeholder="Bullet point">
      <button type="button" class="btn btn-outline-danger" onclick="removePoint(this)">🗑️</button>
    `;
      wrapper.appendChild(div);
    }

    window.removePoint = function(btn) {
      btn.closest('.point-field')?.remove();
    }
  });
</script>


<?php include '../includes/footer.php'; ?>