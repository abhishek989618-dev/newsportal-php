<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'products', 'delete')) {
    die("❌ Access Denied.");
}

$id = (int)$_GET['id'];
$conn->query("DELETE FROM products WHERE id = $id");
header("Location: index.php");
