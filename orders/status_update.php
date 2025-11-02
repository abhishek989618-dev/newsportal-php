<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$order_id = (int)($_POST['order_id'] ?? 0);
$new_status = $_POST['status'] ?? '';
$role_id = $_SESSION['role_id'];
$user_id = $_SESSION['user_id'];
$is_admin = $role_id == 1;

// Only allow permitted status values
$allowed_statuses = ['pending', 'paid', 'shipped', 'delivered', 'canceled'];
if (!$order_id || !in_array($new_status, $allowed_statuses)) {
    die("❌ Invalid request.");
}

// Only users with 'update' permission can proceed
if (!has_permission($conn, $role_id, 'orders', 'update')) {
    die("❌ Access denied.");
}

// If not admin, verify ownership and permission
if (!$is_admin) {
    $stmt = $conn->prepare("SELECT user_id, status FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $order = $res->fetch_assoc();
    $stmt->close();

    if (!$order || $order['user_id'] != $user_id) {
        die("❌ You don't have permission to modify this order.");
    }

    // Only allow end users or reporters to cancel under specific statuses
    if (in_array($role_id, [6, 7]) && $new_status === 'canceled') {
        $cancelable = ['pending', 'paid', 'unpaid', 'shipping'];
        if (!in_array($order['status'], $cancelable)) {
            die("❌ This order can't be canceled.");
        }
    } else {
        die("❌ Only admin can update to this status.");
    }
}

// Update the order status
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $new_status, $order_id);
$stmt->execute();
$stmt->close();

header("Location: index.php");
exit;
