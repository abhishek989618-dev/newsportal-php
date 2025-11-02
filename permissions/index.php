<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Restrict access to users who have 'view' permission on 'permissions'
if (!has_permission($conn, $_SESSION['role_id'], 'permissions', 'view')) {
    die("❌ Access Denied: You do not have permission to view this page.");
}

// Check for edit/delete permissions
$can_edit   = has_permission($conn, $_SESSION['role_id'], 'permissions', 'edit');
$can_delete = has_permission($conn, $_SESSION['role_id'], 'permissions', 'delete');

// ✅ Fetch all permissions
$res = $conn->query("SELECT * FROM permissions");
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="card shadow border-dark">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">🧩 Permission Matrix</h5>
                <?php if (has_permission($conn, $_SESSION['role_id'], 'permissions', 'create')): ?>
                    <a href="create.php" class="btn btn-sm btn-outline-light">+ Add Permission</a>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <?php if ($res->num_rows === 0): ?>
                    <p class="text-muted">No permissions found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Table Name</th>
                                    <th>Operations</th>
                                    <?php if ($can_edit || $can_delete): ?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($perm = $res->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $perm['id'] ?></td>
                                        <td><?= htmlspecialchars($perm['table_name']) ?></td>
                                        <td><?= htmlspecialchars($perm['operations']) ?></td>
                                        <?php if ($can_edit || $can_delete): ?>
                                            <td>
                                                <?php if ($can_edit): ?>
                                                    <a href="edit.php?id=<?= $perm['id'] ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                                                <?php endif; ?>
                                                <?php if ($can_delete): ?>
                                                    <a href="delete.php?id=<?= $perm['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this permission?')">🗑️ Delete</a>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
