<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$user_id = $_SESSION['user_id'];
$res = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $res->fetch_assoc();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <div class="card shadow border-dark">
    <div class="card-header bg-dark text-white">👤 My Profile</div>
    <div class="card-body">
      <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role_id']) ?></p>
      <a href="edit.php" class="btn btn-sm btn-primary">✏️ Edit Profile</a>
      <a href="change_password.php" class="btn btn-sm btn-warning">🔐 Change Password</a>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>
