<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'products', 'update')) {
    die("❌ Access Denied.");
}

$id = (int)$_GET['id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$price = $_POST['price'];
$category_id = $_POST['category_id'];
$tag_ids = json_encode($_POST['tag_ids'] ?? []);

$image = $_FILES['image']['name'];
if ($image) {
    $path = "../uploads/products/";
    move_uploaded_file($_FILES['image']['tmp_name'], $path . $image);
    $stmt = $conn->prepare("UPDATE products SET title=?, description=?, price=?, category_id=?, tag_ids=?, image=? WHERE id=?");
    $stmt->bind_param("ssdissi", $title, $desc, $price, $category_id, $tag_ids, $image, $id);
} else {
    $stmt = $conn->prepare("UPDATE products SET title=?, description=?, price=?, category_id=?, tag_ids=? WHERE id=?");
    $stmt->bind_param("ssdisi", $title, $desc, $price, $category_id, $tag_ids, $id);
}

$stmt->execute();
header("Location: index.php");
