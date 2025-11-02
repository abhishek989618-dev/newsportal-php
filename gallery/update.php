 
<?php
require '../session.php';
require '../config.php';
$id = (int)$_GET['id'];
$website_ids = json_encode($_POST['website_ids'] ?? []);
$title = $_POST['title'];
$desc = $_POST['description'];
$status = $_POST['status'];

$res = $conn->query("SELECT images FROM gallery WHERE id = $id");
$existing = json_decode($res->fetch_assoc()['images'] ?? '[]', true);

// Handle additional uploads
$uploadDir = '../uploads/gallery/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['name'] as $idx => $name) {
        $tmp = $_FILES['images']['tmp_name'][$idx];
        $fname = uniqid("gallery_") . "_" . basename($name);
        move_uploaded_file($tmp, $uploadDir . $fname);
        $existing[] = $fname;
    }
}

$imagesJson = json_encode($existing);
$stmt = $conn->prepare("UPDATE gallery SET website_id=? title=?, description=?, images=?, status=? WHERE id=?");
$stmt->bind_param("sssssi", $website_ids, $title, $desc, $imagesJson, $status, $id);
$stmt->execute();

header("Location: index.php");
