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
  <div class="card shadow">
    <div class="card-header bg-dark text-white">✏️ Edit Profile</div>
    <div class="card-body">
      <form method="post" action="update.php">
        <div class="mb-3">
          <label>Name</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <button class="btn btn-success">💾 Save Changes</button>
      </form>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>
