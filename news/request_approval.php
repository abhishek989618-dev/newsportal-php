<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

if (!has_permission($conn, $_SESSION['role_id'], 'news', 'request_approval')) {
    die("❌ You do not have permission to request approval.");
}

$news_id = $_GET['id'] ?? 0;
$news_id = intval($news_id);
$user_id = $_SESSION['user_id'];

// Fetch news
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ News not found.");
}

$news = $result->fetch_assoc();

// Only allow author to request approval
if ($_SESSION['role_id'] == 3 && $news['author_id'] != $user_id) {
    die("❌ You can only request approval for your own news.");
}

// Update status
$action = $news['requested_action'] ?? 'create';

$stmt = $conn->prepare("UPDATE news SET status = 'pending_editor', requested_by = ?, requested_action = ? WHERE id = ?");
$stmt->bind_param("isi", $user_id, $action, $news_id);
$stmt->execute();

header("Location: index.php?msg=Request+submitted");
exit;
