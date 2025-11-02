<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Super Admin only
if ($_SESSION['role_id'] != 1) {
    die("❌ Access Denied.");
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid permission ID.");
}

// Fetch permission
$stmt = $conn->prepare("SELECT * FROM permissions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$permission = $stmt->get_result()->fetch_assoc();

if (!$permission) {
    die("Permission not found.");
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_name = trim($_POST['table_name']);
    $operations = trim($_POST['operations']);

    if (!$table_name || !$operations) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE permissions SET table_name = ?, operations = ? WHERE id = ?");
        $stmt->bind_param("ssi", $table_name, $operations, $id);
        if ($stmt->execute()) {
            $success = true;
            $permission['table_name'] = $table_name;
            $permission['operations'] = $operations;
        } else {
            $errors[] = "Failed to update permission.";
        }
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <div class="card shadow border-dark">
        <div class="card-header bg-dark text-white">
            <h5>✏️ Edit Permission</h5>
        </div>
        <div class="card-body">
            <?php if ($success): ?>
                <div class="alert alert-success">✅ Permission updated.</div>
                <a href="index.php" class="btn btn-sm btn-primary">⬅️ Back to Permissions</a>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $e): ?>
                        <div><?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Table Name</label>
                    <input type="text" name="table_name" class="form-control" value="<?= htmlspecialchars($permission['table_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Operations (comma-separated)</label>
                    <input type="text" name="operations" class="form-control" value="<?= htmlspecialchars($permission['operations']) ?>" required>
                </div>
                <button type="submit" class="btn btn-success">💾 Update</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>
