 
<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'programs', 'update')) {
    die("❌ Access Denied.");
}

$id = (int)$_GET['id'];
$current = $conn->query("SELECT status FROM programs WHERE id = $id")->fetch_assoc()['status'];
$newStatus = ($current === 'active') ? 'inactive' : 'active';

$stmt = $conn->prepare("UPDATE programs SET status = ? WHERE id = ?");
$stmt->bind_param("si", $newStatus, $id);
$stmt->execute();

header("Location: index.php");
