 
<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'programs', 'create')) {
    die("❌ Access Denied.");
}

$title = $_POST['title'];
$desc = $_POST['description'];
$websites = json_encode($_POST['website_ids'] ?? []);
$status = $_POST['status'];
$image = null;
if (!empty($_FILES['image']['name'])) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image = uniqid('program_') . '.' . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/programs/$image");
}

$stmt = $conn->prepare("INSERT INTO programs (title, description, website_ids, status, image) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $title, $desc, $websites, $status, $image);

$stmt->execute();

header("Location: index.php");
