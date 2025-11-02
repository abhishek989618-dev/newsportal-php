<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Current user
$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

// Permissions
$can_create = $is_superadmin || has_permission($conn, $role_id, 'advertisements', 'create');
$can_read   = $is_superadmin || has_permission($conn, $role_id, 'advertisements', 'read');
$can_update = $is_superadmin || has_permission($conn, $role_id, 'advertisements', 'update');
$can_delete = $is_superadmin || has_permission($conn, $role_id, 'advertisements', 'delete');

if (!$can_read) {
    die("❌ Access Denied: You do not have permission to view advertisements.");
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

// Total
$totalRes = $conn->query("SELECT COUNT(*) as total FROM advertisements");
$total = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch advertisements
$stmt = $conn->prepare("SELECT * FROM advertisements ORDER BY id DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$res = $stmt->get_result();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-4">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📢 Advertisements</h5>
        <?php if ($can_create): ?>
          <a href="create.php" class="btn btn-sm btn-outline-light">+ Add Advertisement</a>
        <?php endif; ?>
      </div>

      <div class="card-body">
        <p>Total Entries: <strong><?= $total ?></strong></p>

        <?php if ($res->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Type</th>
                  <th>Position</th>
                  <th>Status</th>
                  <th>Preview</th>
                  <?php if ($can_update || $can_delete): ?>
                    <th>Actions</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $res->fetch_assoc()): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><span class="badge bg-secondary"><?= ucfirst($row['ad_type'] ?? 'image') ?></span></td>
                    <td>
                      <?php
                        $positions = json_decode($row['position_id'], true) ?? [];
                        foreach ($positions as $pid) {
                          $posRes = $conn->query("SELECT name FROM positions WHERE id = " . (int)$pid);
                          $pos = $posRes->fetch_assoc();
                          echo '<span class="badge bg-info text-dark me-1">' . htmlspecialchars($pos['name'] ?? $pid) . '</span>';
                        }
                      ?>
                    </td>
                    <td>
                      <span class="badge <?= $row['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= ucfirst($row['status']) ?>
                      </span>
                    </td>
                    <td>
                      <?php
                      switch ($row['ad_type']) {
                        case 'youtube':
                          if (!empty($row['youtube_url'])) {
                            $yt_id = preg_replace('/.*(?:v=|\/)([a-zA-Z0-9_-]{11}).*/', '$1', $row['youtube_url']);
                            echo '<iframe width="120" height="80" src="https://www.youtube.com/embed/' . $yt_id . '" frameborder="0" allowfullscreen></iframe>';
                          } else {
                            echo '<span class="text-muted">No URL</span>';
                          }
                          break;
                        case 'external':
                          echo '<a href="' . htmlspecialchars($row['external_url']) . '" target="_blank">Open Banner</a>';
                          break;
                        case 'video':
                          if (!empty($row['media_path'])) {
                            echo '<video src="../uploads/ads/' . htmlspecialchars($row['media_path']) . '" width="100" controls></video>';
                          } else {
                            echo '<span class="text-muted">No Video</span>';
                          }
                          break;
                        default:
                          if (!empty($row['media_path'])) {
                            echo '<img src="../uploads/ads/' . htmlspecialchars($row['media_path']) . '" width="60" class="rounded shadow-sm">';
                          } else {
                            echo '<span class="text-muted">No Image</span>';
                          }
                      }
                      ?>
                    </td>
                    <?php if ($can_update || $can_delete): ?>
                      <td>
                        <?php if ($can_update): ?>
                          <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                        <?php endif; ?>
                        <?php if ($can_delete): ?>
                          <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this advertisement?')">🗑️ Delete</a>
                        <?php endif; ?>
                      </td>
                    <?php endif; ?>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-between align-items-center mt-3">
            <div>Page <?= $page ?> of <?= $totalPages ?></div>
            <div>
              <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn btn-sm btn-outline-dark">⬅ Prev</a>
              <?php endif; ?>
              <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn btn-sm btn-outline-dark">Next ➡</a>
              <?php endif; ?>
            </div>
          </div>
        <?php else: ?>
          <div class="alert alert-warning">No advertisements found.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
