<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'products', 'update')) {
    die("❌ Access Denied: You do not have permission to edit products.");
}

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $res->fetch_assoc();

$selected_tags = json_decode($product['tag_ids'] ?? '[]', true) ?? [];
$categories = $conn->query("SELECT * FROM categories");
$tags = $conn->query("SELECT * FROM tags");
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-5">
  <div class="card shadow border-dark">
    <div class="card-header bg-dark text-white">✏️ Edit Product</div>
    <div class="card-body">
      <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">
        <div class="mb-3">
          <label>Name</label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($product['title']) ?>" required>
        </div>
        <div class="mb-3">
          <label>Description</label>
          <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
          <label>Price</label>
          <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" required>
        </div>
        <div class="mb-3">
          <label>Category</label>
          <select name="category_id" class="form-select" required>
            <?php while ($cat = $categories->fetch_assoc()): ?>
              <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="mb-3">
          <label>Tags</label>
          <select name="tag_ids[]" class="form-select" multiple>
            <?php while ($tag = $tags->fetch_assoc()): ?>
              <option value="<?= $tag['id'] ?>" <?= in_array($tag['id'], $selected_tags) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tag['name']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="mb-3">
          <label>Image</label>
          <input type="file" name="image" class="form-control">
          <?php if ($product['image']): ?>
            <img src="../uploads/products/<?= htmlspecialchars($product['image']) ?>" width="100" class="mt-2">
          <?php endif; ?>
        </div>
        <button class="btn btn-success w-100">💾 Update</button>
      </form>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
