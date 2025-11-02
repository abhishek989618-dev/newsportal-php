<?php
require '../session.php';
require '../config.php';

$user_id = $_SESSION['user_id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);

$stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
$stmt->bind_param("ssi", $name, $email, $user_id);
$stmt->execute();

$_SESSION['user_name'] = $name;
header("Location: index.php");
exit;
