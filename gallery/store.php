 
<?php
require '../session.php';
require '../config.php';
$website_ids = json_encode($_POST['website_ids'] ?? []);
$title = $_POST['title'];
$desc  = $_POST['description'];
$status = $_POST['status'];

$filenames = [];
if (!empty($_FILES['images']['name'][0])) {
    $uploadDir = '../uploads/gallery/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    foreach ($_FILES['images']['name'] as $idx => $name) {
        $tmp = $_FILES['images']['tmp_name'][$idx];
        $fname = uniqid("gallery_") . "_" . basename($name);
        move_uploaded_file($tmp, $uploadDir . $fname);
        $filenames[] = $fname;
    }
}

$imagesJson = json_encode($filenames);
$stmt = $conn->prepare("INSERT INTO gallery (website_id, title, description, images, status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $website_ids, $title, $desc, $imagesJson, $status);
$stmt->execute();

header("Location: index.php");
