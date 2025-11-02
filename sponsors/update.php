 
<?php
require '../session.php';
require '../config.php';

$id = $_GET['id'];
$website_ids = json_encode($_POST['website_ids'] ?? []);
$name = $_POST['name'];
$website = $_POST['website'];
$status = $_POST['status'];

// Keep existing logo unless new one is uploaded
$logo = $conn->query("SELECT logo FROM sponsors WHERE id = $id")->fetch_assoc()['logo'];

if (!empty($_FILES['logo']['name'])) {
    $uploadDir = '../uploads/sponsors/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $fileName = uniqid('sponsor_') . '_' . basename($_FILES['logo']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
        $logo = $fileName;
    }
}

$stmt = $conn->prepare("UPDATE sponsors SET website_id=?, name=?, logo=?, website=?, status=? WHERE id=?");
$stmt->bind_param("sssssi", $website_ids, $name, $logo, $website, $status, $id);
$stmt->execute();

header("Location: index.php");
exit;
