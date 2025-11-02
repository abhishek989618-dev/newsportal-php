 
<?php
require '../session.php';
require '../config.php';

$website_ids = json_encode($_POST['website_ids'] ?? []);
$name = $_POST['name'];
$designation = $_POST['designation'];
$status = $_POST['status'];

$photo = '';
if (!empty($_FILES['photo']['name'])) {
    $uploadDir = "../uploads/teams/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $photo = uniqid("team_") . "_" . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photo);
}

$stmt = $conn->prepare("INSERT INTO teams (website_id, name, designation, photo, status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $website_ids, $name, $designation, $photo, $status);
$stmt->execute();

header("Location: index.php");
