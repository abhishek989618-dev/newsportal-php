<?php
require '../session.php';
require '../config.php';

// Only allow role_id = 1 (super admin)
if ($_SESSION['role_id'] != 4) {
    die("Access denied. Super admin only.");
}
?>

<h1>Super Admin Dashboard</h1>

<ul>
    <li><a href="../news/index.php">📰 Manage News</a></li>
    <li><a href="../roles/index.php">👥 Manage Roles</a></li>
    <li><a href="../permissions/index.php">🛡️ Manage Permissions</a></li>
    <li><a href="../categories/index.php">📁 Manage Categories</a></li>
    <li><a href="../tags/index.php">🏷️ Manage Tags</a></li>
    <li><a href="../websites/index.php">🌐 Manage Websites</a></li>
    <li><a href="../api_keys/index.php">🔑 Manage API Keys</a></li>
    <li><a href="../users/index.php">👤 Manage Users</a></li>
</ul>
