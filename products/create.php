<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';
if (!has_permission($conn, $_SESSION['role_id'], 'products', 'create')) {
  die("Access Denied");
}

$categories = $conn->query("SELECT id, name FROM categories");
$tags = $conn->query("SELECT id, name FROM tags");
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <h4>➕ Add Product</h4>
  <form method="post" action="store.php" enctype="multipart/form-data">
    <div class="mb-3">
      <input type="text" name="title" class="form-control" placeholder="Product Title" required>
    </div>
    <div class="mb-3">
      <textarea name="description" class="form-control" placeholder="Description" rows="3"></textarea>
    </div>
    <div class="mb-3">
      <input type="number" name="price" class="form-control" placeholder="Price" required>
    </div>
    <div class="mb-3">
      <select name="category_id" class="form-select" required>
        <option value="">-- Select Category --</option>
        <?php while ($c = $categories->fetch_assoc()): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Select Tags</label>
      <select name="tags[]" class="form-select" multiple>
        <?php while ($t = $tags->fetch_assoc()): ?>
          <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Upload Image</label>
      <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-success w-100">Save Product</button>
  </form>
</div>
</div>
<?php include '../includes/footer.php'; ?>
