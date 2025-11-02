 
<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'delete')) {
    die("Permission denied.");
}

$id = $_GET['id'];
$conn->query("DELETE FROM news WHERE id = $id");
header("Location: index.php");
