<?php
session_start();
require '../vendor/autoload.php';

use Razorpay\Api\Api;
// use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// These would typically come from AJAX or a previous step
$razorpay_order_id = $_GET['razorpay_order_id'] ?? '';
$amount = $_GET['amount'] ?? 0; // in paise
$currency = $_GET['currency'] ?? 'INR';
$order_id = $_GET['order_id'] ?? 0;

if (!$razorpay_order_id || !$amount || !$order_id) {
    die("Invalid payment request.");
}

// Razorpay key (test key here)
$razorpay_api_key = $_ENV['RAZORPAY_KEY'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complete Your Payment</title>
 <!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h2>Redirecting to Razorpay...</h2>

    <script>
    var options = {
        "key": "<?= $razorpay_api_key ?>",
        "amount": "<?= $amount ?>",
        "currency": "<?= $currency ?>",
        "name": "News Portal",
        "description": "Order #<?= $order_id ?>",
        "order_id": "<?= $razorpay_order_id ?>",
        "handler": function (response){
            // ✅ Save payment_id to server
            window.location.href = "../orders/verify_payment.php?order_id=<?= $order_id ?>&payment_id=" + response.razorpay_payment_id;
        },
        "prefill": {
            "name": "<?= $_SESSION['user_name'] ?? 'Customer' ?>",
            "email": "<?= $_SESSION['user_email'] ?? '' ?>"
        },
        "theme": {
            "color": "#528FF0"
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
    </script>
</body>
</html>
