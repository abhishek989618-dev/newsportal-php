<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../db.php';  // adjust path based on your file structure
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .cart-icon {
      position: relative;
      display: inline-block;
    }
    .cart-badge {
      position: absolute;
      top: -8px;
      right: -10px;
      background: red;
      color: white;
      font-size: 12px;
      padding: 2px 5px;
      border-radius: 50%;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
  <a class="navbar-brand" href="#">MyShop</a>
  <div class="ms-auto">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="../user/cart.php" class="cart-icon me-3">
        🛒
        <?php
          $uid = $_SESSION['user_id'];
          $res = $conn->query("SELECT COUNT(*) FROM cart WHERE user_id = $uid AND status='Pending'");
          $count = $res->fetch_row()[0];
          if ($count > 0): ?>
            <span class="cart-badge"><?= $count ?></span>
        <?php endif; ?>
      </a>
      <a href="../auth/logout.php" class="btn btn-sm btn-danger">Logout</a>
    <?php else: ?>
      <a href="../auth/login.php" class="btn btn-sm btn-success me-2">Login</a>
      <a href="../auth/register.php" class="btn btn-sm btn-primary">Register</a>
    <?php endif; ?>
  </div>
</nav>
<div class="container mt-4">
