 
<?php
require '../session.php';
require '../config.php';

$website_ids = json_encode($_POST['website_ids'] ?? []);
$logo = '';
if (!empty($_FILES['logo']['name'])) {
    $uploadDir = "../uploads/logos/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $fileName = uniqid("logo_") . "_" . basename($_FILES['logo']['name']);
    $targetPath = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
        $logo = $fileName;
    }
}

$stmt = $conn->prepare("INSERT INTO scholarship (website_id, organization_name, title, description, apply_link, logo, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $website_ids, $_POST['organization_name'], $_POST['title'], $_POST['description'], $_POST['apply_link'], $logo, $_POST['status']);
$stmt->execute();

header("Location: index.php");
