<?php
require '../session.php';

if (!in_array($_SESSION['role_id'], [6, 7])) {
    die("❌ Access Denied.");
}

$id = (int)$_GET['id'];
unset($_SESSION['cart'][$id]);
header("Location: index.php");
