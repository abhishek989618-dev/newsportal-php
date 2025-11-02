 
<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'programs', 'delete')) {
    die("❌ Access Denied.");
}

$id = (int)$_GET['id'];
$conn->query("DELETE FROM programs WHERE id = $id");

header("Location: index.php");
