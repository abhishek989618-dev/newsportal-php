<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'users', 'update')) {
    die("❌ Access Denied: You do not have permission to edit users.");
}

$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM users WHERE id = $id");
if (!$res || $res->num_rows === 0) die("User not found");
$user = $res->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $_POST['name'], $_POST['email'], $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>
<?php include '../includes/sidebar.php'?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow border-dark">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">✏️ Edit User</h5>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label for="name" class="form-label">Full Name</label>
              <input type="text" name="name" id="name" class="form-control" required
                value="<?= htmlspecialchars($user['name']) ?>">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" name="email" id="email" class="form-control" required
                value="<?= htmlspecialchars($user['email']) ?>">
            </div>

            <button type="submit" class="btn btn-primary w-100">Update User</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
