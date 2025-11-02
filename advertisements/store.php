<?php
require '../session.php';
require '../config.php';

// Collect common form fields
$title = $_POST['title'];
$link = $_POST['link'] ?? '';
$status = $_POST['status'];
$ad_type = $_POST['ad_type'] ?? 'image';

$website_ids = json_encode($_POST['website_ids'] ?? []);
$position_ids = isset($_POST['position_ids']) ? json_encode($_POST['position_ids']) : null;

$media_path = '';
$youtube_url = $_POST['youtube_url'] ?? '';
$external_url = $_POST['external_url'] ?? '';

// Handle file uploads based on ad type
if (in_array($ad_type, ['image', 'gif', 'video']) && !empty($_FILES['file']['name'])) {
    $uploadDir = '../uploads/ads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $originalName = basename($_FILES['file']['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $media_path = uniqid("ad_") . "." . strtolower($extension);

    move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $media_path);
}

// Prepare INSERT
$stmt = $conn->prepare("
    INSERT INTO advertisements (website_id, title, link, ad_type, media_path, youtube_url, external_url, position_id, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssssssss",
    $website_ids,
    $title,
    $link,
    $ad_type,
    $media_path,
    $youtube_url,
    $external_url,
    $position_ids,
    $status
);

$stmt->execute();

// Redirect to list page
header("Location: index.php");
exit;
