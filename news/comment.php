<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

$news_id = $_GET['id'] ?? null;
if (!$news_id) {
    die("❌ Invalid news ID.");
}

// Check permission
$role_id = $_SESSION['role_id'];
$is_superadmin = $role_id == 1;
$can_comment = $is_superadmin || has_permission($conn, $role_id, 'news', 'comment');

if (!$can_comment) {
    die("❌ Access Denied.");
}

// Fetch news
$stmt = $conn->prepare("SELECT id, title, notes FROM news WHERE id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();
$news = $stmt->get_result()->fetch_assoc();

if (!$news) {
    die("❌ News not found.");
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = trim($_POST['comment']);
    if ($comment !== "") {
        $stmt = $conn->prepare("UPDATE news SET notes = ? WHERE id = ?");
        $stmt->bind_param("si", $comment, $news_id);
        $stmt->execute();
        $success = "✅ Comment added successfully.";
        // Refresh the data
        $stmt = $conn->prepare("SELECT id, title, notes FROM news WHERE id = ?");
        $stmt->bind_param("i", $news_id);
        $stmt->execute();
        $news = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "Comment cannot be empty.";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0">💬 Comment on: <?= htmlspecialchars($news['title']) ?></h5>
    </div>
    <div class="card-body">
      <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      
      <form method="post">
        <div class="mb-3">
          <label for="comment" class="form-label">Your Comment</label>
          <textarea name="comment" id="comment" rows="5" class="form-control" required><?= htmlspecialchars($news['notes'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">💾 Save Comment</button>
        <a href="index.php" class="btn btn-secondary ms-2">🔙 Back</a>
      </form>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>
