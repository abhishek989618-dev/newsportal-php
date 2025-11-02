 
<?php
require '../session.php';
require '../config.php';

$id = (int)$_GET['id'];
$website_ids = json_encode($_POST['website_ids'] ?? []);
$name = $_POST['name'];
$designation = $_POST['designation'];
$message = $_POST['message'];
$status = $_POST['status'];

// Optional image replacement
$res = $conn->query("SELECT image FROM testimonials WHERE id = $id");
$old = $res->fetch_assoc();
$image = $old['image'];

if (!empty($_FILES['image']['name'])) {
    $uploadDir = "../uploads/testimonials/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $image = uniqid("testimonial_") . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
}

$stmt = $conn->prepare("UPDATE testimonials SET website_id=?, name=?, designation=?, message=?, image=?, status=? WHERE id=?");
$stmt->bind_param("ssssssi", $website_ids, $name, $designation, $message, $image, $status, $id);
$stmt->execute();

header("Location: index.php");
