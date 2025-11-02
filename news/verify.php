<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Check permission to verify news
if (!has_permission($conn, $_SESSION['role_id'], 'news', 'verify')) {
    die("❌ You do not have permission to verify news.");
}

// ✅ Get news ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("❌ Invalid news ID.");
}

// ✅ Check if the news item exists
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("❌ News not found.");
}

$news = $res->fetch_assoc();

// ✅ Only allow verifying if status is 'pending_verification'
if ($news['status'] !== 'pending_verification') {
    die("⚠️ This news cannot be verified. Current status: " . $news['status']);
}

// ✅ Update status to 'verified'
$stmt = $conn->prepare("UPDATE news SET status = 'verified', verified_by = ?, updated_at = NOW() WHERE id = ?");
$stmt->bind_param("ii", $_SESSION['user_id'], $id);
$stmt->execute();

// ✅ Redirect
header("Location: index.php?verified=1");
exit;
