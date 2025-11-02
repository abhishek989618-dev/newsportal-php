<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Enforce update permission on 'websites'
if (!has_permission($conn, $_SESSION['role_id'], 'websites', 'update')) {
    die("❌ Access Denied: You do not have permission to manage social links for websites.");
}

// ✅ Sanitize website_id
$website_id = intval($_GET['id'] ?? 0);
if (!$website_id) {
    die("Invalid website ID.");
}

// ✅ Load all platforms
$platforms = $conn->query("SELECT * FROM social_platforms");

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete old links
    $conn->query("DELETE FROM website_social_links WHERE website_id = $website_id");

    foreach ($_POST['social'] as $platform_id => $url) {
        if (!empty($url)) {
            $stmt = $conn->prepare("INSERT INTO website_social_links (website_id, platform_id, url) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $website_id, $platform_id, $url);
            $stmt->execute();
        }
    }
    echo "<p style='color:green;'>✅ Social links updated.</p>";
}

// ✅ Load existing links
$existing = [];
$res = $conn->query("SELECT * FROM website_social_links WHERE website_id = $website_id");
while ($row = $res->fetch_assoc()) {
    $existing[$row['platform_id']] = $row['url'];
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow border-dark">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">🔗 Manage Social Media Links</h5>
        </div>
        <div class="card-body">
          <form method="post">
            <?php while ($p = $platforms->fetch_assoc()): ?>
              <div class="mb-3">
                <label for="platform_<?= $p['id'] ?>" class="form-label">
                  <?= htmlspecialchars($p['name']) ?>
                </label>
                <input type="url"
                       id="platform_<?= $p['id'] ?>"
                       name="social[<?= $p['id'] ?>]"
                       value="<?= htmlspecialchars($existing[$p['id']] ?? '') ?>"
                       class="form-control"
                       placeholder="https://<?= strtolower($p['name']) ?>.com/your-page">
              </div>
            <?php endwhile; ?>

            <button type="submit" class="btn btn-primary w-100">💾 Save Social Links</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


</div>
<?php include '../includes/footer.php'; ?>
