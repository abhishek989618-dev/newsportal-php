<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'approve')) {
    die("❌ You do not have permission to approve news.");
}

$news_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$editor_id = $_SESSION['user_id'];

// Fetch the news record
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("❌ News item not found.");
}

$news = $res->fetch_assoc();

// Only allow approval if status is pending_editor
if ($news['status'] !== 'pending_editor') {
    die("⚠️ News is not pending for editor approval.");
}

// Update status and log approver
$stmt = $conn->prepare("
    UPDATE news 
    SET status = 'pending_verification', approved_by_editor = ?, updated_at = NOW()
    WHERE id = ?
");
$stmt->bind_param("ii", $editor_id, $news_id);
$stmt->execute();

header("Location: index.php?msg=News+approved+successfully");
exit;
