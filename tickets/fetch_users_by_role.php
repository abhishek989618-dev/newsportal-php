<?php
require '../session.php';
require '../config.php';

$role_id = (int)($_GET['role_id'] ?? 0);
if (!$role_id) {
    echo "<option value=''>-- Invalid role --</option>";
    exit;
}

$res = $conn->prepare("SELECT id, name FROM users WHERE role_id = ?");
$res->bind_param("i", $role_id);
$res->execute();
$result = $res->get_result();

if ($result->num_rows > 0) {
    while ($u = $result->fetch_assoc()) {
        echo "<option value='{$u['id']}'>" . htmlspecialchars($u['name']) . "</option>";
    }
} else {
    echo "<option value=''>No users found for this role</option>";
}
