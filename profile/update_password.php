<?php
require '../session.php';
require '../config.php';

$user_id = $_SESSION['user_id'];
$current = $_POST['current'];
$new = $_POST['new'];
$confirm = $_POST['confirm'];

$res = $conn->query("SELECT password FROM users WHERE id = $user_id");
$hash = $res->fetch_assoc()['password'];

if (!password_verify($current, $hash)) {
    die("❌ Current password incorrect.");
}

if ($new !== $confirm) {
    die("❌ New passwords do not match.");
}

$new_hash = password_hash($new, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $new_hash, $user_id);
$stmt->execute();

header("Location: index.php");
