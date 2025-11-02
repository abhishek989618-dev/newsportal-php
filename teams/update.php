 
<?php
require '../session.php';
require '../config.php';
$id = (int)$_GET['id'];
$website_ids = json_encode($_POST['website_ids'] ?? []);
$name = $_POST['name'];
$designation = $_POST['designation'];
$status = $_POST['status'];

$res = $conn->query("SELECT photo FROM teams WHERE id = $id");
$old = $res->fetch_assoc();
$photo = $old['photo'];

if (!empty($_FILES['photo']['name'])) {
    $uploadDir = "../uploads/teams/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $photo = uniqid("team_") . "_" . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photo);
}

$stmt = $conn->prepare("UPDATE teams SET website_id=?, name=?, designation=?, photo=?, status=? WHERE id=?");
$stmt->bind_param("sssssi", $website_ids, $name, $designation, $photo, $status, $id);
$stmt->execute();

header("Location: index.php");
