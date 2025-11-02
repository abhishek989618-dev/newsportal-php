<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';
// ✅ Allow only super admin
if ($_SESSION['role_id'] != 1) {
    die("❌ Access Denied: Only Super Admin can create roles.");
}

// ✅ Handle role creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_name = trim($_POST['role_name']);

    if ($role_name) {
        $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
        $stmt->bind_param("s", $role_name);
        $stmt->execute();
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>⚠️ Role name is required.</p>";
    }
}
?>
<?php include '../includes/sidebar.php'?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">➕ Add New Role</h5>
        </div>
        <div class="card-body">
          <form method="post" action="">
            <div class="mb-3">
              <label for="role_name" class="form-label">Role Name</label>
              <input type="text" class="form-control" id="role_name" name="role_name" required placeholder="Enter role name (e.g. editor, manager)">
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Role</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>
