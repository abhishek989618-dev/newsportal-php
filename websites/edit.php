<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Check permission
if (!has_permission($conn, $_SESSION['role_id'], 'websites', 'update')) {
    die("❌ Access Denied: You do not have permission to update websites.");
}

// ✅ Securely get the ID
$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM websites WHERE id = $id");

if ($res->num_rows === 0) {
    die("❌ Website not found.");
}

$site = $res->fetch_assoc();

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle logo upload
    $newLogo = $site['logo']; // keep existing

    if (!empty($_FILES['logo']['name'])) {
        $uploadDir = "../uploads/logos/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileName = uniqid("logo_") . "_" . basename($_FILES['logo']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
            $newLogo = $fileName;
        }
    }

    $stmt = $conn->prepare("UPDATE websites SET name=?, domain=?, email=?, phone=?, address=?, status=?, logo=? WHERE id=?");
    $stmt->bind_param("sssssssi", $_POST['name'], $_POST['domain'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['status'], $newLogo, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<?php include '../includes/sidebar.php'?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow border-dark">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">🛠️ Edit Website</h5>
        </div>
        <div class="card-body">
          <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="name" class="form-label">Website Name</label>
              <input type="text" class="form-control" id="name" name="name" required
                     value="<?= htmlspecialchars($site['name']) ?>">
            </div>

            <div class="mb-3">
              <label for="domain" class="form-label">Domain</label>
              <input type="url" class="form-control" id="domain" name="domain" required
                     value="<?= htmlspecialchars($site['domain']) ?>" placeholder="https://example.com">
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($site['email']) ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone"
                       value="<?= htmlspecialchars($site['phone']) ?>">
              </div>
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($site['address']) ?></textarea>
            </div>

            <div class="mb-3">
              <label for="logo" class="form-label">Upload New Logo (optional)</label>
              <input type="file" class="form-control" name="logo" id="logo">
              <?php if (!empty($site['logo'])): ?>
                <p class="mt-2">Current Logo:</p>
                <img src="../uploads/logos/<?= htmlspecialchars($site['logo']) ?>" width="100" alt="Logo" class="rounded shadow">
              <?php endif; ?>
            </div>

            <div class="mb-4">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" name="status" id="status">
                <option value="active" <?= $site['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $site['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
              </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">💾 Update Website</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>