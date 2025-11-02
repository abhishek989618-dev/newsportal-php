<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Check permission: "create" on "websites"
if (!has_permission($conn, $_SESSION['role_id'], 'websites', 'create')) {
    die("❌ Access Denied: You don't have permission to create websites.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Upload logo if present
    $logo = '';
    if (!empty($_FILES['logo']['name'])) {
        $uploadDir = "../uploads/logos/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileName = uniqid("logo_") . "_" . basename($_FILES['logo']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
            $logo = $fileName;
        }
    }

    $stmt = $conn->prepare("INSERT INTO websites (name, domain, email, phone, address, status, logo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $_POST['name'], $_POST['domain'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['status'], $logo);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>


<?php include '../includes/sidebar.php'?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>
    <div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card p-4">
        <h4 class="mb-4 text-center"><i class="fas fa-globe me-2"></i>Add New Website</h4>

        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Website Name</label>
            <input type="text" class="form-control" name="name" placeholder="My Awesome Site" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Domain</label>
            <input type="url" class="form-control" name="domain" placeholder="https://example.com" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" placeholder="admin@example.com">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Phone</label>
              <input type="text" class="form-control" name="phone" placeholder="+91-9876543210">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea class="form-control" name="address" placeholder="Full office address..." rows="2"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Logo</label>
            <input type="file" class="form-control" name="logo">
          </div>

          <div class="mb-4">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
              <option value="active" selected>Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

          <button type="submit" class="btn btn-save w-100"><i class="fas fa-save me-2"></i>Save Website</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>