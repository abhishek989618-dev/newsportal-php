<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Check permission to create positions
if (!has_permission($conn, $_SESSION['role_id'], 'positions', 'create')) {
    die("❌ Access Denied: You do not have permission to add positions.");
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name) {
        $stmt = $conn->prepare("INSERT INTO positions (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>⚠️ position name cannot be empty.</p>";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">🏷️ Add New position</h5>
        </div>

        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label for="name" class="form-label">position Name</label>
              <input type="text"
                     name="name"
                     id="name"
                     class="form-control"
                     required
                     placeholder="Enter position name (e.g. trending, popular)">
            </div>

            <button type="submit" class="btn btn-success w-100">➕ Save position</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


</div>
<?php include '../includes/footer.php'; ?>