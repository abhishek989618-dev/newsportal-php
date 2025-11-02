<?php
require '../config.php';

$order_id = (int)($_GET['order_id'] ?? 0);
$payment_id = $_GET['payment_id'] ?? '';

if ($order_id && $payment_id) {
    $stmt = $conn->prepare("UPDATE orders SET razorpay_payment_id = ?, status = 'paid' WHERE id = ?");
    $stmt->bind_param("si", $payment_id, $order_id);
    if ($stmt->execute()) {
        header("Location: ../payment/payment-success.php");
         exit;

        unset($_SESSION['cart']); // clear cart
    } else {
        echo "❌ Failed to save payment ID.";
    }
} else {
    echo "❌ Invalid data.";
}
