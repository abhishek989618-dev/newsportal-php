<?php
require '../config.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    die("No news slug provided.");
}

$stmt = $conn->prepare("SELECT * FROM news WHERE slug = ? AND status = 'published'");
$stmt->bind_param("s", $slug);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "News not found.";
    exit;
}

$news = $res->fetch_assoc();
$news['points'] = json_decode($news['points'] ?? '[]');
$news['image'] = json_decode($news['image'] ?? '[]');
$news['tag_id'] = json_decode($news['tag_id'] ?? '[]');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($news['title']) ?> | News</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f9f9f9;
      font-family: 'Segoe UI', sans-serif;
    }
    .news-container {
      background: white;
      border-radius: 8px;
      padding: 2rem;
      margin-top: 3rem;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    .news-title {
      font-weight: 700;
      font-size: 2rem;
      color: #222;
    }
    .news-meta {
      color: #888;
      font-size: 0.9rem;
    }
    .news-image {
      max-width: 100%;
      border-radius: 6px;
      margin-bottom: 15px;
    }
    .news-section-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-top: 1.5rem;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="news-container">
    <h1 class="news-title"><?= htmlspecialchars($news['title']) ?></h1>
    <p class="news-meta">
      🗓️ <?= htmlspecialchars($news['news_date']) ?> |
      📍 <?= htmlspecialchars($news['location']) ?>
    </p>

    <?php if (!empty($news['image'])): ?>
      <div class="row my-3">
        <?php foreach ($news['image'] as $img): ?>
          <div class="col-md-4 col-6 mb-3">
            <img src="../uploads/news_images/<?= htmlspecialchars($img) ?>" class="news-image" alt="news-image">
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($news['highlights'])): ?>
      <div class="news-section-title">🔦 Highlights</div>
      <p><?= nl2br(htmlspecialchars($news['highlights'])) ?></p>
    <?php endif; ?>

    <div class="news-section-title">📖 Full Content</div>
    <p><?= nl2br(htmlspecialchars($news['content'])) ?></p>

    <?php if (!empty($news['points'])): ?>
      <div class="news-section-title">📌 Key Points</div>
      <ul>
        <?php foreach ($news['points'] as $point): ?>
          <li><?= htmlspecialchars($point) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>

</body>
</html>

