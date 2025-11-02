<nav class="navbar navbar-expand-md navbar-dark shadow-sm mb-4 ">
  <div class="container-fluid">
    <button class="btn btn-outline-secondary d-md-none toggle-sidebar" onclick="toggleSidebar()">☰</button>
    <span class="navbar-brand"><?= $roleName ?> Dashboard</span>
    <span class="navbar-links d-flex">
     
    <a href="/news-portal/notifications/index.php" class="nav-link">
      🔔 <span class="badge bg-danger"><?= $count ?></span>

    </a>
    
<span>
    <a class="nav-link dropdown-toggle text-capitalize" href="/news-portal/#" role="button" data-bs-toggle="dropdown">
      👤 <?= htmlspecialchars($user_name) ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="/news-portal/profile/index.php">👁️ View Profile</a></li>
      <li><a class="dropdown-item" href="/news-portal/profile/edit.php">✏️ Edit Profile</a></li>
      <li><a class="dropdown-item" href="/news-portal/profile/change_password.php">🔒 Change Password</a></li>
      <li>
        <hr class="dropdown-divider">
      </li>
      <li><a class="dropdown-item text-danger" href="/news-portal/auth/logout.php">🚪 Logout</a></li>
    </ul>
    </span>
  </span>
  </div>
</nav>