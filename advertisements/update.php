<?php
require '../session.php';
require '../config.php';

$id = (int)$_GET['id'];
$title = $_POST['title'];
$link = $_POST['link'];
$ad_type = $_POST['ad_type'];
$status = $_POST['status'];

$position_ids = isset($_POST['position_ids']) ? json_encode($_POST['position_ids']) : json_encode([]);
$website_ids = isset($_POST['website_ids']) ? json_encode($_POST['website_ids']) : json_encode([]);

$youtube_url = $_POST['youtube_url'] ?? null;
$external_url = $_POST['external_url'] ?? null;
$media_path = '';

// Get current ad data
$res = $conn->query("SELECT * FROM advertisements WHERE id = $id");
$current = $res->fetch_assoc();
$current_file = $current['media_path'];

// Handle media based on type
if (in_array($ad_type, ['image', 'gif', 'video'])) {
    if (!empty($_FILES['file']['name'])) {
        $uploadDir = '../uploads/ads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $media_path = uniqid("ad_") . '.' . $ext;

        move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $media_path);

        // Optionally delete old file
        if ($current_file && file_exists($uploadDir . $current_file)) {
            unlink($uploadDir . $current_file);
        }
    } else {
        // Keep old file if not uploading a new one
        $media_path = $current_file;
    }
} else {
    $media_path = null;
}

// Handle YouTube and External URLs
$youtube_url = ($ad_type === 'youtube') ? trim($_POST['youtube_url']) : null;
$external_url = ($ad_type === 'external') ? trim($_POST['external_url']) : null;

// Prepare and execute update
$stmt = $conn->prepare("
    UPDATE advertisements 
    SET website_id = ?, title = ?, media_path = ?, link = ?, position_id = ?, status = ?, ad_type = ?, youtube_url = ?, external_url = ?
    WHERE id = ?
");
$stmt->bind_param(
    "sssssssssi",
    $website_ids,
    $title,
    $media_path,
    $link,
    $position_ids,
    $status,
    $ad_type,
    $youtube_url,
    $external_url,
    $id
);
$stmt->execute();

header("Location: index.php");
exit;
