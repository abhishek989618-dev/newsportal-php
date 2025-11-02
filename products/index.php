<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

$can_read   = has_permission($conn, $role_id, 'products', 'read');
$can_create = has_permission($conn, $role_id, 'products', 'create');
$can_update = has_permission($conn, $role_id, 'products', 'update');
$can_delete = has_permission($conn, $role_id, 'products', 'delete');

if (!$can_read) die("❌ Access Denied");

// UI filters
$search = $_GET['search'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$sort = $_GET['sort'] ?? 'latest';

$limit = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$where = "1=1";
$params = [];
$types = "";

// Apply search filters
if ($search !== '') {
    $where .= " AND p.title LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

if (is_numeric($min_price)) {
    $where .= " AND p.price >= ?";
    $params[] = $min_price;
    $types .= "d";
}

if (is_numeric($max_price)) {
    $where .= " AND p.price <= ?";
    $params[] = $max_price;
    $types .= "d";
}

// Sorting logic
switch ($sort) {
    case 'price_asc': $order = "p.price ASC"; break;
    case 'price_desc': $order = "p.price DESC"; break;
    case 'name_asc': $order = "p.title ASC"; break;
    case 'name_desc': $order = "p.title DESC"; break;
    default: $order = "p.id DESC";
}

// Count total
$sql_count = "SELECT COUNT(*) as total FROM products p WHERE $where";
$stmt = $conn->prepare($sql_count);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch products with categories
$sql = "SELECT p.*, c.name as category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE $where
        ORDER BY $order
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$params[] = $offset;
$params[] = $limit;
$types .= "ii";
$stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between mb-3">
    <h4>🛍️ Products</h4>
    <?php if ($can_create): ?>
      <a href="create.php" class="btn btn-success">+ Add Product</a>
    <?php endif; ?>
  </div>

  <!-- Filters -->
  <form class="row g-2 mb-3" method="get">
    <div class="col-md-3">
      <input type="text" name="search" class="form-control" placeholder="🔍 Search by name" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-2">
      <input type="number" name="min_price" class="form-control" placeholder="Min ₹" value="<?= htmlspecialchars($min_price) ?>">
    </div>
    <div class="col-md-2">
      <input type="number" name="max_price" class="form-control" placeholder="Max ₹" value="<?= htmlspecialchars($max_price) ?>">
    </div>
    <div class="col-md-3">
      <select name="sort" class="form-select">
        <option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>>Sort by Latest</option>
        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
        <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name: A-Z</option>
        <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Name: Z-A</option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-dark w-100">Apply</button>
    </div>
  </form>

  <!-- Cards -->
  <div class="row">
    <?php while ($row = $products->fetch_assoc()): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <?php if (!empty($row['image'])): ?>
            <img src="../uploads/products/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" style="height:200px; object-fit:cover">
          <?php endif; ?>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
            <p class="card-text mb-1">₹<?= $row['price'] ?></p>
            <p class="text-muted mb-1">Category: <?= htmlspecialchars($row['category_name'] ?? '—') ?></p>
            <p class="text-muted mb-2">Tags: 
              <?php
              $tag_ids = json_decode($row['tag_ids'] ?? '[]', true);
              if ($tag_ids) {
                $idList = implode(',', array_map('intval', $tag_ids));
                $tagNames = [];
                $tagRes = $conn->query("SELECT name FROM tags WHERE id IN ($idList)");
                while ($t = $tagRes->fetch_assoc()) $tagNames[] = $t['name'];
                echo htmlspecialchars(implode(', ', $tagNames));
              } else {
                echo 'None';
              }
              ?>
            </p>

            <div class="mt-auto">
              <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary w-100 mb-1">🔍 View</a>

              <?php if (in_array($role_id, [6, 7])): ?>
                <form method="post" action="../cart/add.php" class="d-grid mb-1">
                  <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                  <input type="hidden" name="quantity" value="1">
                  <button class="btn btn-sm btn-success w-100">🛒 Add to Cart</button>
                </form>
              <?php endif; ?>

              <?php if ($can_update): ?>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning w-100 mb-1">✏️ Edit</a>
              <?php endif; ?>
              <?php if ($can_delete): ?>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger w-100" onclick="return confirm('Delete this product?')">🗑️ Delete</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Pagination -->
  <nav class="mt-4">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">⬅ Prev</a></li>
      <?php endif; ?>
      <?php if ($page < $totalPages): ?>
        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next ➡</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>
</div>

<?php include '../includes/footer.php'; ?>
