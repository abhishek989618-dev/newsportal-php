 
<?php
require '../session.php';
require '../config.php';

$website_ids = json_encode($_POST['website_ids'] ?? []);

$name = $_POST['name'];
$designation = $_POST['designation'];
$message = $_POST['message'];
$status = $_POST['status'];

// Upload image
$image = '';
if (!empty($_FILES['image']['name'])) {
    $uploadDir = "../uploads/testimonials/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $image = uniqid("testimonial_") . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
}

$stmt = $conn->prepare("INSERT INTO testimonials (website_id, name, designation, message, image, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $website_ids, $name, $designation, $message, $image, $status);
$stmt->execute();

header("Location: index.php");
