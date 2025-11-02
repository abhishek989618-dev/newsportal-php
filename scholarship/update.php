 
<?php
require '../session.php';
require '../config.php';

$id = $_GET['id'];
$website_ids = json_encode($_POST['website_ids'] ?? []);
$logo = $_POST['existing_logo'] ?? '';

if (!empty($_FILES['logo']['name'])) {
    $uploadDir = "../uploads/logos/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $fileName = uniqid("logo_") . "_" . basename($_FILES['logo']['name']);
    $targetPath = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
        $logo = $fileName;
    }
}

$stmt = $conn->prepare("UPDATE scholarship SET website_id=?, organization_name=?, title=?, description=?, apply_link=?, logo=?, status=? WHERE id=?");
$stmt->bind_param("sssssssi", $website_ids, $_POST['organization_name'], $_POST['title'], $_POST['description'], $_POST['apply_link'], $logo, $_POST['status'], $id);
$stmt->execute();

header("Location: index.php");
