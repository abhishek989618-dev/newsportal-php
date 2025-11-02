<?php
require '../session.php';
require '../config.php';

// Only reporter (6) or end user (7)
if (!in_array($_SESSION['role_id'], [6, 7])) {
    die("❌ Access Denied.");
}

$product_id = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

// Fetch product
$stmt = $conn->prepare("SELECT id, title, price FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("❌ Invalid product.");
}

$product = $res->fetch_assoc();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ✅ Add or update cart using ID as key
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$product_id] = [
        'id' => $product['id'],
        'name' => $product['title'],
        'price' => $product['price'],
        'quantity' => $quantity
    ];
}

header("Location: ../cart/index.php");
exit;
