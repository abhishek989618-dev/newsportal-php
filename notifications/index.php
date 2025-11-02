<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Permissions
$can_create = has_permission($conn, $_SESSION['role_id'], 'notifications', 'create');
$can_update = has_permission($conn, $_SESSION['role_id'], 'notifications', 'update');

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

// Fetch notifications for user, role, or target_users JSON
$query = "
  SELECT * FROM notifications 
  WHERE user_id = ? 
     OR role_id = ? 
     OR JSON_CONTAINS(target_users, JSON_QUOTE(?), '$') 
  ORDER BY created_at DESC
";
$stmt = $conn->prepare($query);
$user_id_str = (string)$user_id; // JSON_QUOTE expects string
$stmt->bind_param("iis", $user_id, $role_id, $user_id_str);
$stmt->execute();
$res = $stmt->get_result();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>
  <div class="container mt-4">
    <div class="card shadow">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">🔔 Notifications</h5>
        <?php if ($can_create || $can_update): ?>
          <a href="create.php" class="btn btn-sm btn-primary">➕ Create Notification</a>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <?php if ($res->num_rows == 0): ?>
          <div class="alert alert-info">No notifications.</div>
        <?php endif; ?>
        <ul class="list-group">
          <?php while ($n = $res->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center <?= $n['is_read'] ? '' : 'fw-bold' ?>">
              <div>
                <strong><?= htmlspecialchars($n['title']) ?></strong><br>
                <?= htmlspecialchars($n['message']) ?>
                <small class="d-block text-muted"><?= $n['created_at'] ?></small>
              </div>
              <div>
                <?php if (!$n['is_read']): ?>
                  <a href="mark_read.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-success">Mark Read</a>
                <?php endif; ?>
                <a href="delete.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">🗑</a>
              </div>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
