
<?php include '../session.php'; ?>
<?php include '../config.php'; ?>
<?php include '../check_permission.php'; ?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-warning">🔐 Change Password</div>
    <div class="card-body">
      <form method="post" action="update_password.php">
        <div class="mb-3">
          <label>Current Password</label>
          <input type="password" name="current" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>New Password</label>
          <input type="password" name="new" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Confirm New Password</label>
          <input type="password" name="confirm" class="form-control" required>
        </div>
        <button class="btn btn-warning">🔄 Update Password</button>
      </form>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>
