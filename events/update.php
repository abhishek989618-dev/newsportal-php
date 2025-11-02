 
<?php
require '../session.php';
require '../config.php';
$id = (int)$_GET['id'];
$website_ids = json_encode($_POST['website_ids'] ?? []);

$title = $_POST['title'];
$description = $_POST['description'];
$event_date = $_POST['event_date'];
$location = $_POST['location'];
$notes = $_POST['notes'];

$res = $conn->query("SELECT image FROM events WHERE id = $id");
$current = $res->fetch_assoc();
$image = $current['image'];

if (!empty($_FILES['image']['name'])) {
    $uploadDir = '../uploads/events/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $image = uniqid("event_") . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
}

$stmt = $conn->prepare("UPDATE events SET website_id=?, title=?, description=?, event_date=?, location=?, image=?, notes=? WHERE id=?");
$stmt->bind_param("sssssssi", $website_ids, $title, $description, $event_date, $location, $image, $notes, $id);
$stmt->execute();

header("Location: index.php");
