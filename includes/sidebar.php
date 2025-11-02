<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9f9f9;
    }

    .sidebar {
      width: 220px;
      background: #fff;
      height: 100vh;
      overflow-y: auto;
      position: fixed;
      border-right: 1px solid #e1e1e1;
      padding-top: 30px;
      transition: all 0.3s;
      z-index: 1050;
    }

    .sidebar h5 {
      padding-left: 20px;
    }

    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: #333;
      text-decoration: none;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: #f0f0f0;
      font-weight: 500;
    }

    .main {
      margin-left: 220px;
      padding: 30px;
    }

    .form-section {
      background: white;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
      align-items: center;
    }

    .image-preview {
      width: 100%;
      height: 250px;
      background-color: #eaeaea;
      border: 2px dashed #ccc;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      font-size: 14px;
      color: #777;
      margin-bottom: 10px;
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .close-sidebar-btn {
      display: none;
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 20px;
      color: #999;
      background: none;
      border: none;
    }

    .navbar {
      background: linear-gradient(to right, #4bc0c8, #c779d0, #feac5e);
    }

    .card {
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
      border-radius: 10px;
    }

    @media(max-width: 768px) {
      .sidebar {
        left: -250px;
        position: absolute;
      }

      .sidebar.active {
        left: 0;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
      }

      .main {
        margin-left: 0;
      }

      .toggle-sidebar {
        display: inline-block;
        margin-bottom: 20px;
      }

      .close-sidebar-btn {
        display: block;
      }
    }
  </style>
</head>

<body>

  <div class="sidebar" id="sidebar">
    <button class="close-sidebar-btn" onclick="toggleSidebar()">×</button>
    <h5 class="mb-4">News Dashboad</h5>

  

<a href="index.php" class="active"><i class="fas fa-chart-line me-2"></i> Graph</a>

<?php if (has_permission($conn, $role_id, 'users', 'read')): ?>
  <a href="/news-portal/users/"><i class="fas fa-users me-2"></i> Manage Users</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'roles', 'read')): ?>
  <a href="/news-portal/roles/"><i class="fas fa-user-shield me-2"></i> Manage Roles</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'permissions', 'read')): ?>
  <a href="/news-portal/permissions/"><i class="fas fa-unlock-alt me-2"></i> Manage Permissions</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'news', 'read')): ?>
  <a href="/news-portal/news/"><i class="fas fa-newspaper me-2"></i> News</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'events', 'read')): ?>
  <a href="/news-portal/events/">📅 Events</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'programs', 'read')): ?>
  <a href="/news-portal/programs/">🤝 Programs</a>
<?php endif; ?>

<!-- <?php if (has_permission($conn, $role_id, 'forms', 'read')): ?>
  <a href="/news-portal/forms/">🤝 Forms</a>
<?php endif; ?> -->

<?php if (has_permission($conn, $role_id, 'advertisements', 'read')): ?>
  <a href="/news-portal/advertisements/">📢 Advertisements</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'scroller', 'read')): ?>
  <a href="/news-portal/scroller/">🤝 Updates</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'scholarship', 'read')): ?>
  <a href="/news-portal/scholarship/">🤝 Scholarships</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'teams', 'read')): ?>
  <a href="/news-portal/teams/">👥 Teams</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'gallery', 'read')): ?>
  <a href="/news-portal/gallery/">🖼️ Gallery</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'sponsors', 'read')): ?>
  <a href="/news-portal/sponsors/">🤝 Sponsors</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'testimonials', 'read')): ?>
  <a href="/news-portal/testimonials/">📣 Testimonials</a>
<?php endif; ?>

<?php if (
  has_permission($conn, $role_id, 'products', 'read') ||
  has_permission($conn, $role_id, 'orders', 'read') ||
  has_permission($conn, $role_id, 'cart', 'read')
): ?>
  <div class="dropdown">
    <a href="/news-portal/#" class="nav-link dropdown-toggle" id="productDropdown" role="button" data-bs-toggle="dropdown">
      🤝 Products
    </a>
    <ul class="dropdown-menu">
      <?php if (has_permission($conn, $role_id, 'products', 'read')): ?>
        <li><a class="dropdown-item" href="/news-portal/products/">Products</a></li>
      <?php endif; ?>
      <?php if (has_permission($conn, $role_id, 'orders', 'read')): ?>
        <li><a class="dropdown-item" href="/news-portal/orders/">Orders</a></li>
      <?php endif; ?>
      <?php if (has_permission($conn, $role_id, 'cart', 'read')): ?>
        <li><a class="dropdown-item" href="/news-portal/cart/">Cart</a></li>
      <?php endif; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'categories', 'read')): ?>
  <a href="/news-portal/categories/"><i class="fas fa-folder-open me-2"></i> Categories</a>
<?php endif; ?>
<?php if (has_permission($conn, $role_id, 'types', 'read')): ?>
  <a href="/news-portal/types/"><i class="fas fa-folder-open me-2"></i> Types</a>
<?php endif; ?>
<?php if (has_permission($conn, $role_id, 'tags', 'read')): ?>
  <a href="/news-portal/tags/"><i class="fas fa-tags me-2"></i> Tags</a>
<?php endif; ?>
<?php if (has_permission($conn, $role_id, 'devices', 'read')): ?>
  <a href="/news-portal/devices/"><i class="fas fa-tags me-2"></i> Devices</a>
<?php endif; ?>
<?php if (has_permission($conn, $role_id, 'positions', 'read')): ?>
  <a href="/news-portal/positions/"><i class="fas fa-tags me-2"></i> positions</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'websites', 'read')): ?>
  <a href="/news-portal/websites/"><i class="fas fa-globe me-2"></i> Websites</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'api_keys', 'read')): ?>
  <a href="/news-portal/api_keys/"><i class="fas fa-key me-2"></i> API Keys</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'tickets', 'read')): ?>
  <a href="/news-portal/tickets/">🤝 Tickets</a>
<?php endif; ?>

<?php if (has_permission($conn, $role_id, 'payments', 'read')): ?>
  <a href="/news-portal/payments/">🤝 Payments</a>
<?php endif; ?>

    <a href="/news-portal/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
  </div>