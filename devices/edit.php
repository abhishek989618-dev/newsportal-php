<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Check permission to update devices
if (!has_permission($conn, $_SESSION['role_id'], 'devices', 'update')) {
    die("❌ Access Denied: You do not have permission to edit devices.");
}

// ✅ Sanitize device ID
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Invalid device ID.");
}

// ✅ Fetch device
$res = $conn->query("SELECT * FROM devices WHERE id = $id");
if ($res->num_rows === 0) {
    die("❌ device not found.");
}
$device = $res->fetch_assoc();

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name) {
        $stmt = $conn->prepare("UPDATE devices SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>⚠️ device name cannot be empty.</p>";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
    <?php include '../includes/navbar.php'?>
    <div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">✏️ Edit device</h5>
        </div>

        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label for="name" class="form-label">device Name</label>
              <input type="text"
                     name="name"
                     id="name"
                     class="form-control"
                     value="<?= htmlspecialchars($device['name']) ?>"
                     required
                     placeholder="Enter device name">
            </div>
            <button type="submit" class="btn btn-primary w-100">💾 Update device</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>