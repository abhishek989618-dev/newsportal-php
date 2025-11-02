 
<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'programs', 'read')) {
    die("❌ Access Denied.");
}

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM programs WHERE id = $id");
$p = $res->fetch_assoc();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-dark text-white">
      <h5><?= htmlspecialchars($p['title']) ?> (#<?= $p['id'] ?>)</h5>
    </div>
    <div class="card-body">
      <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($p['description'])) ?></p>
      <p><strong>Status:</strong> <span class="badge bg-<?= $p['status'] == 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($p['status']) ?></span></p>
      <p><strong>Websites:</strong>
        <?php
        $ids = json_decode($p['website_ids'] ?? '[]', true);
        if ($ids) {
          $in = implode(',', array_map('intval', $ids));
          $res = $conn->query("SELECT name FROM websites WHERE id IN ($in)");
          while ($w = $res->fetch_assoc()) echo "<span class='badge bg-info me-1'>{$w['name']}</span>";
        } else {
          echo "None";
        }
        ?>
      </p>
      <?php if (!empty($p['image'])): ?>
  <p><strong>Image:</strong><br>
    <img src="../uploads/programs/<?= $p['image'] ?>" width="200" class="img-thumbnail">
  </p>
<?php endif; ?>

    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
