<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$is_admin = $role_id == 1;
$can_read = $is_admin || has_permission($conn, $role_id, 'orders', 'read');
if (!$can_read) die("Access denied");

$query = $is_admin
    ? "SELECT o.*, u.name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC"
    : "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";

$res = $conn->query($query);
include '../includes/sidebar.php';
?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <h4>🛒 Orders</h4>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Status</th>
        <th>Amount</th>
        <th>Created</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
      <?php
        $status = strtolower($row['status']);
        $badge_class = match($status) {
          'delivered' => 'success',
          'shipped'   => 'warning',
          'canceled'  => 'danger',
          default     => 'info'
        };
      ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $is_admin ? htmlspecialchars($row['name']) : 'You' ?></td>
        <td><span class="badge bg-<?= $badge_class ?>"><?= ucfirst($status) ?></span></td>
        <td>₹<?= number_format($row['amount'], 2) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
          <?php if (!in_array($status, ['delivered', 'canceled'])): ?>
            <form method="post" action="status_update.php" class="d-inline">
              <input type="hidden" name="order_id" value="<?= $row['id'] ?>">

              <?php if ($is_admin): ?>
                <?php if ($status !== 'shipped'): ?>
                  <button name="status" value="shipped" class="btn btn-sm btn-primary">Shipped</button>
                <?php endif; ?>

                <?php if ($status === 'shipped'): ?>
                  <button name="status" value="delivered" class="btn btn-sm btn-success">Delivered</button>
                <?php endif; ?>
              <?php endif; ?>

              <?php
                $cancelable = in_array($status, ['pending', 'paid', 'unpaid', 'shipping']);
                $can_cancel = in_array($role_id, [6, 7]) && $cancelable;
                if ($can_cancel):
              ?>
                <button name="status" value="canceled" class="btn btn-sm btn-danger">Cancel</button>
              <?php endif; ?>
            </form>
          <?php else: ?>
            <span class="text-muted">No action</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</div>
<?php include '../includes/footer.php'; ?>
