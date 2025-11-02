<?php
require '../session.php';
require '../config.php';

$order_id = $_POST['order_id'];
$razorpay_payment_id = $_POST['razorpay_payment_id'];

$stmt = $conn->prepare("UPDATE orders SET status = 'paid', razorpay_payment_id = ? WHERE id = ?");
$stmt->bind_param("si", $razorpay_payment_id, $order_id);
$stmt->execute();

unset($_SESSION['cart']);
header("Location: index.php?paid=1");
