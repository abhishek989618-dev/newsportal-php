<?php
require '../session.php';
require '../config.php';
include '../check_permission.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

// Count total
$totalRes = $conn->query("SELECT COUNT(*) as total FROM gallery");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch paginated gallery
$stmt = $conn->prepare("SELECT * FROM gallery ORDER BY id DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$res = $stmt->get_result();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
  <div class="card shadow border-dark">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">🖼️ Gallery</h5>
      <a href="create.php" class="btn btn-sm btn-outline-light">+ Add Gallery</a>
    </div>

    <div class="card-body">
      <p>Total Entries: <strong><?= $total ?></strong></p>

      <?php if ($res->num_rows > 0): ?>
        <div class="row">
          <?php while ($row = $res->fetch_assoc()): ?>
            <div class="col-md-6 mb-4">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <h5><?= htmlspecialchars($row['title']) ?></h5>
                  <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                  <div class="d-flex flex-wrap">
                    <?php
                      $imgs = json_decode($row['images'] ?? '[]', true);
                      foreach ($imgs as $img) {
                        echo "<img src='../uploads/gallery/{$img}' width='80' class='me-2 mb-2 rounded shadow'>";
                      }
                    ?>
                  </div>
                  <div class="mt-2">
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this gallery item?')">🗑️ Delete</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div>Page <?= $page ?> of <?= $totalPages ?></div>
          <div>
            <?php if ($page > 1): ?>
              <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-dark btn-sm">⬅ Prev</a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
              <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-dark btn-sm">Next ➡</a>
            <?php endif; ?>
          </div>
        </div>

      <?php else: ?>
        <div class="alert alert-warning">No gallery entries found.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
