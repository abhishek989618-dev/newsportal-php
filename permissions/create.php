<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';
// ✅ Super Admin only
if ($_SESSION['role_id'] != 1) {
    die("❌ Access Denied: Only Super Admin can create permissions.");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = trim($_POST['table']);
    $ops   = $_POST['ops'] ?? [];

    if ($table && !empty($ops)) {
        $ops_str = implode(',', $ops); // convert array to comma-separated
        $stmt = $conn->prepare("INSERT INTO permissions (table_name, operations) VALUES (?, ?)");
        $stmt->bind_param("ss", $table, $ops_str);
        $stmt->execute();
        header("Location: index.php");
        exit;
    } else {
        $error = "Table name and at least one operation are required.";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-dark">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">➕ Add New Permission</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Table Name</label>
                                <input type="text" name="table" class="form-control" placeholder="e.g. news, users" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Operations</label>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleCheckboxes(true)">Select All</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="toggleCheckboxes(false)">Unselect All</button>
                                </div>
                                <div class="row">
                                    <?php
                                    $operations = [
                                        "create", "read", "update", "delete",
                                        "request_approval", "approve", "deny", "pending",
                                        "comment", "reject", "assign", "accept",
                                        "decline", "error", "publish", "unpublish",
                                        "verify", "block"
                                    ];
                                    foreach ($operations as $index => $op):
                                    ?>
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="ops[]" value="<?= $op ?>" id="op<?= $index ?>">
                                                <label class="form-check-label" for="op<?= $index ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $op)) ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">💾 Save Permission</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCheckboxes(status) {
  const checkboxes = document.querySelectorAll("input[type='checkbox'][name='ops[]']");
  checkboxes.forEach(cb => cb.checked = status);
}
</script>

<?php include '../includes/footer.php'; ?>
