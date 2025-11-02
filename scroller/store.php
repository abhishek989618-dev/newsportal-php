 
<?php
require '../session.php';
require '../config.php';

$website_ids = json_encode($_POST['website_ids'] ?? []);
$text = $_POST['text'];
$status = $_POST['status'];

$stmt = $conn->prepare("INSERT INTO scroller (website_id, text, status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sss",$website_ids, $text, $status);
$stmt->execute();

header("Location: index.php");
