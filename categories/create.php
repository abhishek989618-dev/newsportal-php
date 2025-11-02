<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Enforce permission: create on categories
if (!has_permission($conn, $_SESSION['role_id'], 'categories', 'create')) {
    die("❌ Access Denied: You do not have permission to add categories.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>Category name is required.</p>";
    }
}
?>

<?php include '../includes/sidebar.php'?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow border-dark">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">📁 Add New Category</h5>
        </div>
        <div class="card-body">
          <form method="post" action="">
            <div class="mb-3">
              <label for="name" class="form-label">Category Name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter category name" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">➕ Save Category</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>
