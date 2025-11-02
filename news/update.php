<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'update')) {
    die("Permission denied.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch existing media
$stmt = $conn->prepare("SELECT media FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$news = $res->fetch_assoc();

if (!$news) {
    die("News not found.");
}

// === Handle form inputs ===
$title        = $_POST['title'] ?? '';
$category_id  = $_POST['category_id'] ?? null;
$type_id      = $_POST['type_id'] ?? null;
$websites     = $_POST['website_ids'] ?? [];
$tags         = $_POST['tag_ids'] ?? [];
$devices      = $_POST['device_ids'] ?? [];
$positions    = $_POST['position_ids'] ?? [];
$content      = $_POST['content'] ?? '';
$location     = $_POST['location'] ?? '';
$highlights   = $_POST['highlights'] ?? '';
$points       = $_POST['points'] ?? [];
$news_date    = $_POST['news_date'] ?? '';
$notes        = $_POST['notes'] ?? '';
$existingMedia = $_POST['existing_media'] ?? [];

// JSON encode
$websites_json  = json_encode($websites);
$tags_json      = json_encode($tags);
$devices_json   = json_encode($devices);
$positions_json = json_encode($positions);
$points_json    = json_encode(array_values(array_filter($points)));

// === Upload new media files ===
$upload_dir = '../uploads/news_media/';
$final_media = $existingMedia;

// Process new uploads
if (!empty($_FILES['media']['name'][0])) {
    foreach ($_FILES['media']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['media']['error'][$index] === 0) {
            $originalName = basename($_FILES['media']['name'][$index]);
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $newName = uniqid('media_') . '.' . $ext;
            $targetPath = $upload_dir . $newName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $final_media[] = $newName;
            }
        }
    }
}

// Delete removed media
$old_media = json_decode($news['media'], true) ?? [];
$removed_media = array_diff($old_media, $existingMedia);

foreach ($removed_media as $file) {
    $filePath = $upload_dir . $file;
    if (file_exists($filePath)) {
        unlink($filePath); // Delete from server
    }
}

// JSON encode final media
$media_json = json_encode($final_media);

// === Update DB ===
$update_stmt = $conn->prepare("
    UPDATE news SET
        title = ?,
        category_id = ?,
        type_id = ?,
        website_id = ?,
        tag_id = ?,
        device_id = ?,
        position_id = ?,
        content = ?,
        location = ?,
        highlights = ?,
        points = ?,
        news_date = ?,
        notes = ?,
        media = ?
    WHERE id = ?
");

$update_stmt->bind_param(
    "siisssssssssssi",
    $title,
    $category_id,
    $type_id,
    $websites_json,
    $tags_json,
    $devices_json,
    $positions_json,
    $content,
    $location,
    $highlights,
    $points_json,
    $news_date,
    $notes,
    $media_json,
    $id
);

if ($update_stmt->execute()) {
    header("Location: index.php?message=News updated successfully");
    exit;
} else {
    die("Update failed: " . $conn->error);
}
?>
