 
<?php
require '../session.php';
require '../config.php';

$website_ids = json_encode($_POST['website_ids'] ?? []);
$title = $_POST['title'];
$description = $_POST['description'];
$event_date = $_POST['event_date'];
$location = $_POST['location'];
$notes = $_POST['notes'];


$image = '';
if (!empty($_FILES['image']['name'])) {
    $uploadDir = '../uploads/events/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $image = uniqid("event_") . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
}

$stmt = $conn->prepare("INSERT INTO events (website_id, title, description, event_date, location, image, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $website_ids, $title, $description, $event_date, $location, $image, $notes);
$stmt->execute();

header("Location: index.php");
