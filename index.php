 
<?php
require 'session.php';
require 'config.php';
require 'check_permission.php';

$role_id = $_SESSION['role_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

// Fetch role name
$roleName = 'Unknown';
if ($role_id) {
    $stmt = $conn->prepare("SELECT role_name FROM roles WHERE id = ?");
    $stmt->bind_param("i", $role_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $roleName = ucfirst($row['role_name']);
    }
}
?>
<?php
// Fetch news count by status
$newsData = $conn->query("
    SELECT status, COUNT(*) as count FROM news GROUP BY status
");
$newsStats = [];
while ($row = $newsData->fetch_assoc()) {
    $newsStats[$row['status']] = $row['count'];
}

// Users per role
$roleData = $conn->query("
    SELECT r.role_name, COUNT(u.id) as total
    FROM users u
    JOIN roles r ON u.role_id = r.id
    GROUP BY u.role_id
");
$userRoleStats = [];
while ($row = $roleData->fetch_assoc()) {
    $userRoleStats[] = ["role" => $row['role_name'], "total" => $row['total']];
}

// Other counts
$totalCategories = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc()['total'];
$totalTags = $conn->query("SELECT COUNT(*) as total FROM tags")->fetch_assoc()['total'];
?>


<?php include 'includes/sidebar.php' ?>
<!-- Main Content -->
<div class="main">
    <!-- Header -->
   <?php include 'includes/navbar.php'; ?>

    <h2>Welcome to the News Portal Dashboard</h2>
    <p>Role: <strong><?= $roleName ?></strong></p>

    <h4>Permissions on <code>news</code> Table:</h4>
    <ul>
        <?php foreach (['create', 'read', 'update', 'delete'] as $action): ?>
            <li>
                <?= ucfirst($action) ?>: 
                <?= has_permission($conn, $role_id, 'news', $action) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>" ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- <div class="row">
  <div class="col-md-6">
    <canvas id="newsChart"></canvas>
  </div>
  <div class="col-md-6">
    <canvas id="userChart"></canvas>
  </div>
</div>

<div class="mt-4">
  <h4>Other Stats:</h4>
  <ul>
    <li>Total Categories: <?= $totalCategories ?></li>
    <li>Total Tags: <?= $totalTags ?></li>
  </ul>
</div>


    <a href="news/index.php" class="btn btn-primary">Go to News Module</a>
    <a href="auth/logout.php" class="btn btn-danger">Logout</a>-->
</div>
<!-- Footer -->
<?php include 'includes/footer.php'; ?>
