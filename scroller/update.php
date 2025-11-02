 
<?php
require '../session.php';
require '../config.php';
$id = (int)$_GET['id'];
$website_ids = json_encode($_POST['website_ids'] ?? []);

$text = $_POST['text'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE scroller SET website_id=?, text=?, status=? WHERE id=?");
$stmt->bind_param("sssi", $website_ids, $text, $status, $id);
$stmt->execute();

header("Location: index.php");
