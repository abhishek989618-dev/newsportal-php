<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;

// Define all permissions
$can_publish   = $is_superadmin || has_permission($conn, $role_id, 'news', 'publish');
$can_unpublish = $is_superadmin || has_permission($conn, $role_id, 'news', 'unpublish');

$can_create   = $is_superadmin || has_permission($conn, $role_id, 'news', 'create');
$can_read     = $is_superadmin || has_permission($conn, $role_id, 'news', 'read');
$can_update   = $is_superadmin || has_permission($conn, $role_id, 'news', 'update');
$can_delete   = $is_superadmin || has_permission($conn, $role_id, 'news', 'delete');

$can_request  = $is_superadmin || has_permission($conn, $role_id, 'news', 'request_approval');
$can_approve  = $is_superadmin || has_permission($conn, $role_id, 'news', 'approve');
$can_deny     = $is_superadmin || has_permission($conn, $role_id, 'news', 'deny');
$can_pending  = $is_superadmin || has_permission($conn, $role_id, 'news', 'pending');
$can_comment  = $is_superadmin || has_permission($conn, $role_id, 'news', 'comment');
$can_reject   = $is_superadmin || has_permission($conn, $role_id, 'news', 'reject');
$can_assign   = $is_superadmin || has_permission($conn, $role_id, 'news', 'assign');
$can_accept   = $is_superadmin || has_permission($conn, $role_id, 'news', 'accept');
$can_decline  = $is_superadmin || has_permission($conn, $role_id, 'news', 'decline');

if (!$can_read) die("❌ Access Denied.");

$limit  = 10;
$page   = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

// Count total
if ($role_id == 3 && !$is_superadmin) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM news WHERE author_id = ?");
    $stmt->bind_param("i", $user_id);
} else {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM news ORDER BY created_at DESC");
}
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch paginated news
if ($role_id == 6 && !$is_superadmin) {
    $stmt = $conn->prepare("SELECT * FROM news WHERE author_id = ? ORDER BY created_at DESC LIMIT ?, ?");
    $stmt->bind_param("iii", $user_id, $offset, $limit);
} else {
    $stmt = $conn->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $limit);
}
$stmt->execute();
$res = $stmt->get_result();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <div class="card shadow bg-white">
    <div class="card-header bg-dark text-light d-flex justify-content-between align-items-center">
      <h5 class="mb-0">📰 All News</h5>
      <?php if ($can_create): ?>
        <a href="create.php" class="btn btn-sm btn-outline-light">+ Create News</a>
      <?php endif; ?>
    </div>

    <div class="card-body">
      <p>Total Entries: <strong><?= $total ?></strong></p>

      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Unique ID</th>
              <th>Title</th>
              <th>Date</th>
              <th>Status</th>
              <th>Slug</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($n = $res->fetch_assoc()): ?>
              <tr>
                <td><?= $n['id'] ?></td>
                <td><?= htmlspecialchars($n['unique_news_id']) ?></td>
                <td><?= htmlspecialchars($n['title']) ?></td>
                <td><?= htmlspecialchars($n['news_date']) ?></td>
                <td><?= ucfirst($n['status']) ?></td>
                <td><?= htmlspecialchars($n['slug']) ?></td>
                <td class="d-flex flex-wrap gap-1 align-items-start">
  <!-- EDIT (always visible if allowed) -->
  <?php if ($can_update): ?>
    <a href="edit.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
  <?php endif; ?>

  <!-- DELETE (always visible if allowed by your existing logic) -->
  <?php
  $canShowDelete =
      $can_delete &&
      !($role_id == 3 || $role_id == 6) || // If not reporter, show always
      ($role_id == 3 || $role_id == 6) && $n['status'] !== 'verified'; // If reporter, only if not verified
  ?>
  <?php if ($canShowDelete): ?>
    <a href="delete.php?id=<?= $n['id'] ?>"
       class="btn btn-sm btn-danger"
       onclick="return confirm('Delete this news item?')">🗑️ Delete</a>
  <?php endif; ?>

  <!-- MORE ACTIONS DROPDOWN -->
  <div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle"
        type="button"
        id="moreActionsDropdown-<?= $n['id'] ?>"
        onclick="toggleMoreActions(<?= $n['id'] ?>)">
  ⋯ More
</button>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="moreActionsDropdown-<?= $n['id'] ?>">
      <?php if ($can_request && $n['status'] === 'draft'): ?>
        <li><a class="dropdown-item" href="request_approval.php?id=<?= $n['id'] ?>">📤 Request approval</a></li>
      <?php endif; ?>

      <?php if ($can_approve && $n['status'] === 'pending_editor'): ?>
        <li><a class="dropdown-item" href="approve.php?id=<?= $n['id'] ?>">✅ Approve</a></li>
      <?php endif; ?>

      <?php if ($can_deny && in_array($n['status'], ['pending_editor', 'pending_verification'])): ?>
        <li><a class="dropdown-item" href="deny.php?id=<?= $n['id'] ?>">🚫 Deny</a></li>
      <?php endif; ?>

      <?php if ($can_pending): ?>
        <li><a class="dropdown-item" href="pending.php?id=<?= $n['id'] ?>">⏳ Mark Pending</a></li>
      <?php endif; ?>

      <?php if ($can_comment): ?>
        <li><a class="dropdown-item" href="comment.php?id=<?= $n['id'] ?>">💬 Comment</a></li>
      <?php endif; ?>

      <?php if ($can_reject): ?>
        <li><a class="dropdown-item" href="reject.php?id=<?= $n['id'] ?>">❌ Reject</a></li>
      <?php endif; ?>

      <?php if ($can_assign): ?>
        <li><a class="dropdown-item" href="assign.php?id=<?= $n['id'] ?>">🧩 Assign</a></li>
      <?php endif; ?>

      <?php if ($can_accept): ?>
        <li><a class="dropdown-item" href="accept.php?id=<?= $n['id'] ?>">👍 Accept</a></li>
      <?php endif; ?>

      <?php if ($can_decline): ?>
        <li><a class="dropdown-item" href="decline.php?id=<?= $n['id'] ?>">👎 Decline</a></li>
      <?php endif; ?>

      <?php
      // Publish/Unpublish: render as a form inside dropdown so POST works
      if (($n['status'] === 'verified' || $n['status'] === 'published') && ($can_publish || $can_unpublish)): ?>
        <li>
          <form method="post" action="toggle_publish.php" class="px-3 py-1 m-0">
            <input type="hidden" name="id" value="<?= $n['id'] ?>">
            <input type="hidden" name="action" value="<?= $n['status'] === 'published' ? 'unpublish' : 'publish' ?>">
            <button type="submit" class="btn btn-sm w-100 text-start dropdown-item">
              <?= $n['status'] === 'published' ? '🔕 Unpublish' : '📢 Publish' ?>
            </button>
          </form>
        </li>
      <?php endif; ?>

      <?php if (!empty($n['slug'])): ?>
        <li>
          <a class="dropdown-item" href="../public/news.php?slug=<?= urlencode($n['slug']) ?>" target="_blank">
            🔗 View
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</td>

              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center">
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
    </div>
  </div>
</div>


</div>
<script>
  function toggleMoreActions(id) {
    const menu = document.querySelector(`#moreActionsDropdown-${id} + .dropdown-menu`);
    if (!menu) return;

    // Toggle show/hide class manually
    menu.classList.toggle('show');
  }

  // Optional: close dropdown if clicked outside
  document.addEventListener('click', function (e) {
    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
      if (!menu.parentElement.contains(e.target)) {
        menu.classList.remove('show');
      }
    });
  });
</script>

<?php include '../includes/footer.php'; ?>
