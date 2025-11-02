<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'users', 'update')) {
    die("❌ Access Denied: You do not have permission to assign roles.");
}

$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM users WHERE id = $id");
if (!$res || $res->num_rows === 0) die("User not found");
$user = $res->fetch_assoc();

// On submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_id = intval($_POST['role_id']);

    // Update the user's role
    $stmt = $conn->prepare("UPDATE users SET role_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $role_id, $id);
    $stmt->execute();

    // Get the role name
    $roleRes = $conn->prepare("SELECT role_name FROM roles WHERE id = ?");
    $roleRes->bind_param("i", $role_id);
    $roleRes->execute();
    $roleResult = $roleRes->get_result();
    $role_name = $roleResult->fetch_assoc()['role_name'] ?? 'Unknown Role';

    // Fetch permissions for that role from role_permissions and permissions tables
    $permQuery = $conn->prepare("
        SELECT p.table_name, p.operations 
        FROM role_permissions rp
        JOIN permissions p ON p.id = rp.permission_id
        WHERE rp.role_id = ?
    ");
    $permQuery->bind_param("i", $role_id);
    $permQuery->execute();
    $permRes = $permQuery->get_result();

    $permissions = [];
    while ($row = $permRes->fetch_assoc()) {
        $permissions[] = $row['table_name'] . ': ' . $row['operations'];
    }

    $permissions_text = !empty($permissions) ? implode("\n", $permissions) : "No permissions assigned.";

    // Notify the user about the role assignment
    $title = "🔑 New Role Assigned";
    $type = 2; // You can define your own numeric type mapping
    $message = "You have been assigned the role: $role_name. Permissions: $permissions_text";
    $is_read = 0;

    $notif = $conn->prepare("
        INSERT INTO notifications (title, type, message, user_id, is_read, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $notif->bind_param("sssii", $title, $type, $message, $id, $is_read);
    $notif->execute();

    header("Location: index.php");
    exit;
}


// Get all roles
$roles = $conn->query("SELECT * FROM roles");
?>
<?php include '../includes/sidebar.php'?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

    <div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow border-dark">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">🔁 Assign Role to <?= htmlspecialchars($user['name']) ?></h5>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label for="role_id" class="form-label">Select Role</label>
              <select name="role_id" id="role_id" class="form-select" required>
                <?php while ($r = $roles->fetch_assoc()): ?>
                  <option value="<?= $r['id'] ?>" <?= $r['id'] == $user['role_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($r['role_name']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Role</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
