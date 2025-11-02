<?php
require '../session.php';

if (!in_array($_SESSION['role_id'], [6, 7])) {
    die("❌ Access Denied.");
}

$id = (int)$_POST['id'];
$qty = max(1, (int)$_POST['qty']);

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] = $qty;
}

header("Location: index.php");
