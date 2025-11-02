<?php
require '../session.php';
require '../config.php';
require '../vendor/autoload.php';

use Razorpay\Api\Api;
// use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize Razorpay API
$razorpay = new Api($_ENV['RAZORPAY_KEY'], $_ENV['RAZORPAY_SECRET']);

$user_id = $_SESSION['user_id'] ?? 0;
$cart = $_SESSION['cart'] ?? [];

if ($user_id <= 0 || empty($cart)) {
    die("❌ Invalid session or cart.");
}

// Calculate total
$total_amount = 0;
foreach ($cart as $item) {
    if (!isset($item['price'], $item['quantity'])) continue;
    $total_amount += $item['price'] * $item['quantity'];
}

if ($total_amount <= 0) {
    die("❌ Invalid total amount.");
}

// ✅ Create Razorpay order
try {
    $order = $razorpay->order->create([
        'receipt' => 'order_rcptid_' . time(),
        'amount' => $total_amount * 100,
        'currency' => 'INR'
    ]);
} catch (Exception $e) {
    die("❌ Razorpay error: " . $e->getMessage());
}

$razorpay_order_id = $order->id;

// Save order to DB
$stmt = $conn->prepare("INSERT INTO orders (user_id, amount, status, razorpay_order_id) VALUES (?, ?, 'pending', ?)");
$stmt->bind_param("ids", $user_id, $total_amount, $razorpay_order_id);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// ✅ Insert order items
foreach ($cart as $item) {
    if (!isset($item['id'], $item['quantity'], $item['price'])) {
        echo "❌ Invalid cart item: " . json_encode($item) . "<br>";
        continue;
    }

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);

    if (!$stmt->execute()) {
        echo "❌ Failed to insert item ID {$item['id']}: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

// ✅ Final redirect to payment
header("Location: ../payment/payment.php?razorpay_order_id={$razorpay_order_id}&amount=" . ($total_amount * 100) . "&currency=INR&order_id={$order_id}");
exit;
