<?php
require '../session.php';

if (!in_array($_SESSION['role_id'], [6, 7])) {
    die("❌ Access Denied.");
}

unset($_SESSION['cart']);
header("Location: index.php");
