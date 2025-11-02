<?php
require '../session.php';
require '../config.php';
include '../check_permission.php';

// Allow only end-user (7) and reporter (6)
if (!in_array($_SESSION['role_id'], [6, 7])) {
    die("❌ Access Denied.");
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    die("🛒 Your cart is empty.");
}

$totalAmount = 0;
foreach ($cart as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-dark text-white">
      <h5>🧾 Checkout</h5>
    </div>
    <div class="card-body">
      <form method="post" action="../orders/place.php">
        <div class="row">
          <div class="col-md-6">
            <h6>📦 Shipping Details</h6>
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Address</label>
              <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>
          </div>

          <div class="col-md-6">
            <h6>🛒 Order Summary</h6>
            <ul class="list-group mb-3">
              <?php foreach ($cart as $id => $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)
                  <span>₹<?= $item['price'] * $item['quantity'] ?></span>
                </li>
              <?php endforeach; ?>
              <li class="list-group-item d-flex justify-content-between">
                <strong>Total:</strong>
                <strong>₹<?= $totalAmount ?></strong>
              </li>
            </ul>

            <!-- Hidden input for total -->
            <input type="hidden" name="total_amount" value="<?= $totalAmount ?>">
            <button type="submit" class="btn btn-success w-100">🛍️ Place Order</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>
