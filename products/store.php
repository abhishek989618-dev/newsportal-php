<?php
require '../session.php';
require '../config.php';

$title = $_POST['title'];
$description = $_POST['description'];
$price = (float)$_POST['price'];
$category_id = (int)$_POST['category_id'];
$tags = json_encode($_POST['tags'] ?? []);
$created_by = $_SESSION['user_id'];

$image = '';
if ($_FILES['image']['name']) {
  $name = time() . "_" . basename($_FILES['image']['name']);
  move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/products/$name");
  $image = $name;
}

$stmt = $conn->prepare("INSERT INTO products (title, description, price, category_id, tag_ids, image, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdisss", $title, $description, $price, $category_id, $tags, $image, $created_by);
$stmt->execute();

header("Location: index.php");
