<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'create')) {
    die("Permission denied.");
}

// Basic fields
$title       = $_POST['title'];
$slug        = strtolower(str_replace(' ', '-', $title));
$author_id   = $_SESSION['user_id'];
$unique_id   = "NEWS-" . date("YmdHis");

$category_id = $_POST['category_id'] ?? null;
$type_id     = $_POST['type_id'] ?? null;

$content     = $_POST['content'] ?? '';
$location    = $_POST['location'] ?? '';
$highlights  = $_POST['highlights'] ?? '';
$notes       = $_POST['notes'] ?? '';
$news_date   = $_POST['news_date'] ?? '';
$latitude    = $_POST['latitude'] ?? null;
$longitude   = $_POST['longitude'] ?? null;

// Points as JSON
$points = isset($_POST['points']) ? json_encode(array_filter($_POST['points'])) : null;

// Tags, Devices, Positions, Websites as JSON
$tag_ids      = isset($_POST['tag_ids']) ? json_encode($_POST['tag_ids']) : null;
$device_ids   = isset($_POST['device_ids']) ? json_encode($_POST['device_ids']) : null;
$position_ids = isset($_POST['position_ids']) ? json_encode($_POST['position_ids']) : null;
$website_ids  = isset($_POST['website_ids']) ? json_encode($_POST['website_ids']) : null;

// Media handling
$media_type = $_POST['media_type'] ?? 'image';
$media_data = null;

$upload_dir = "../uploads/news_media/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

switch ($media_type) {
    case 'image':
        $image_names = [];
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $i => $name) {
                $tmp_name = $_FILES['images']['tmp_name'][$i];
                $file_name = uniqid("img_") . "_" . basename($name);
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($tmp_name, $target_path)) {
                    $image_names[] = $file_name;
                }
            }
        }
        $media_data = json_encode(['type' => 'image', 'files' => $image_names]);
        break;

    case 'video':
        if (!empty($_FILES['video']['name'])) {
            $file_name = uniqid("vid_") . "_" . basename($_FILES['video']['name']);
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['video']['tmp_name'], $target_path)) {
                $media_data = json_encode(['type' => 'video', 'file' => $file_name]);
            }
        }
        break;

    case 'gif':
        if (!empty($_FILES['gif']['name'])) {
            $file_name = uniqid("gif_") . "_" . basename($_FILES['gif']['name']);
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['gif']['tmp_name'], $target_path)) {
                $media_data = json_encode(['type' => 'gif', 'file' => $file_name]);
            }
        }
        break;

    case 'youtube':
        $youtube_link = trim($_POST['youtube_link'] ?? '');
        if (filter_var($youtube_link, FILTER_VALIDATE_URL)) {
            $media_data = json_encode(['type' => 'youtube', 'link' => $youtube_link]);
        }
        break;
}

// Prepare and insert
$stmt = $conn->prepare("INSERT INTO news (
    unique_news_id, title, slug, category_id, type_id, author_id,
    content, location, latitude, longitude, highlights,
    points, notes, tag_id, device_id, position_id, website_id,
    media, status, news_date, created_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft', ?, NOW())");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssiisssddsssssssss",
    $unique_id, $title, $slug, $category_id, $type_id, $author_id,
    $content, $location, $latitude, $longitude, $highlights,
    $points, $notes, $tag_ids, $device_ids, $position_ids, $website_ids,
    $media_data, $news_date
);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>
