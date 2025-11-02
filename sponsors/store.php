 
<?php
require '../session.php';
require '../config.php';

$website_ids = json_encode($_POST['website_ids'] ?? []);
$name = $_POST['name'];
$website = $_POST['website'];
$status = $_POST['status'];

// Upload logo
$logo = '';
if (!empty($_FILES['logo']['name'])) {
    $uploadDir = '../uploads/sponsors/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $fileName = uniqid('sponsor_') . '_' . basename($_FILES['logo']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
        $logo = $fileName;
    }
}

$stmt = $conn->prepare("INSERT INTO sponsors (website_id, name, logo, website, status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $website_ids, $name, $logo, $website, $status);
$stmt->execute();

header("Location: index.php");
exit;
