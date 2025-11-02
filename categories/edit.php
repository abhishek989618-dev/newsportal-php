<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Enforce permission: update on categories
if (!has_permission($conn, $_SESSION['role_id'], 'categories', 'update')) {
    die("❌ Access Denied: You do not have permission to edit categories.");
}

// ✅ Sanitize and fetch
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Invalid category ID.");
}

$res = $conn->query("SELECT * FROM categories WHERE id = $id");
if ($res->num_rows === 0) {
    die("❌ Category not found.");
}
$cat = $res->fetch_assoc();

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>Category name cannot be empty.</p>";
    }
}
?>
    <?php include '../includes/sidebar.php'; ?>

<div class="main">
    <?php include '../includes/navbar.php'; ?> 
    <div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow border-dark">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">✏️ Edit Category</h5>
        </div>
        <div class="card-body">
          <form method="post" action="">
            <div class="mb-3">
              <label for="name" class="form-label">Category Name</label>
              <input type="text" id="name" name="name" class="form-control"
                     value="<?= htmlspecialchars($cat['name']) ?>" required
                     placeholder="Enter category name">
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Category</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>