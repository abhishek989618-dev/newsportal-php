 
<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'programs', 'update')) {
    die("❌ Access Denied.");
}

$id = (int)$_GET['id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$websites = json_encode($_POST['website_ids'] ?? []);
$status = $_POST['status'];

$image = $conn->query("SELECT image FROM programs WHERE id = $id")->fetch_assoc()['image'];

if (!empty($_FILES['image']['name'])) {
    if ($image && file_exists("../uploads/programs/$image")) {
        unlink("../uploads/programs/$image");
    }
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image = uniqid('program_') . '.' . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/programs/$image");
}

$stmt = $conn->prepare("UPDATE programs SET title=?, description=?, website_ids=?, status=?, image=? WHERE id=?");
$stmt->bind_param("sssssi", $title, $desc, $websites, $status, $image, $id);

$stmt->execute();

header("Location: index.php");
