<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'products', 'read')) {
    die("❌ Access Denied.");
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT p.*, c.name AS category FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$tag_ids = json_decode($product['tag_ids'], true) ?? [];

$tag_names = [];
if (!empty($tag_ids)) {
    $tag_id_str = implode(",", array_map('intval', $tag_ids));
    $tags = $conn->query("SELECT name FROM tags WHERE id IN ($tag_id_str)");
    while ($t = $tags->fetch_assoc()) {
        $tag_names[] = $t['name'];
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-dark text-white">📦 Product Details</div>
    <div class="card-body">
      <h4><?= htmlspecialchars($product['title']) ?></h4>
      <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      <p><strong>Price:</strong> ₹<?= $product['price'] ?></p>
      <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
      <p><strong>Tags:</strong> <?= implode(', ', $tag_names) ?></p>
      <?php if ($product['image']): ?>
        <img src="../uploads/products/<?= htmlspecialchars($product['image']) ?>" width="150" class="mt-2">
      <?php endif; ?>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
