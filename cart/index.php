<?php
require '../session.php';
require '../config.php';
include '../check_permission.php';

if (!in_array($_SESSION['role_id'], [6, 7])) {
    die("❌ Access Denied.");
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;

include '../includes/sidebar.php';
?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="card shadow border-dark">
    <div class="card-header bg-dark text-white d-flex justify-content-between">
      <h5>🛍 Your Cart</h5>
      <a href="clear.php" class="btn btn-sm btn-outline-light" onclick="return confirm('Clear cart?')">🗑 Clear All</a>
    </div>
    <div class="card-body">
      <?php if (empty($cart)): ?>
        <div class="alert alert-warning">Your cart is empty.</div>
      <?php else: ?>
        <table class="table table-bordered align-middle">
          <thead class="table-dark">
            <tr><th>#</th><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($cart as $id => $item): 
              $subtotal = $item['quantity'] * $item['price'];
              $total += $subtotal;
            ?>
              <tr>
                <td><?= $id ?></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>₹<?= number_format($item['price'], 2) ?></td>
                <td>
                  <form method="post" action="update.php" class="d-flex gap-1">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="number" name="qty" value="<?= $item['quantity'] ?>" min="1" class="form-control form-control-sm" style="width: 70px;">
                    <button class="btn btn-sm btn-secondary">↻</button>
                  </form>
                </td>
                <td>₹<?= number_format($subtotal, 2) ?></td>
                <td>
                  <a href="remove.php?id=<?= $id ?>" class="btn btn-sm btn-danger">🗑</a>
                </td>
              </tr>
            <?php endforeach; ?>
            <tr class="fw-bold">
              <td colspan="4" class="text-end">Total</td>
              <td colspan="2">₹<?= number_format($total, 2) ?></td>
            </tr>
          </tbody>
        </table>
        <a href="../orders/checkout.php" class="btn btn-success w-100">💳 Proceed to Checkout</a>
      <?php endif; ?>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
