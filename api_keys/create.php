<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Permission check
if (!has_permission($conn, $_SESSION['role_id'], 'api_keys', 'create')) {
    die("❌ Access Denied: You do not have permission to generate API keys.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $website_id = $_POST['website_id'];

    // ✅ Generate secure API key
    $api_key = bin2hex(random_bytes(16)); // 32 characters

    // ✅ Insert into database
    $stmt = $conn->prepare("INSERT INTO api_keys (api_key, website_id) VALUES (?, ?)");
    $stmt->bind_param("si", $api_key, $website_id);
    $stmt->execute();

    echo '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>API Key Generated</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #0f0f2f;
      color: #f0f0f0;
      font-family: "Segoe UI", sans-serif;
    }
    .alert-card {
      background: linear-gradient(to right, #1a1a3d, #0f0f2f);
      color: #fff;
      border: none;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 195, 255, 0.2);
      padding: 2rem;
    }
    .alert-card h5 {
      font-weight: 600;
    }
    .alert-card code {
      background-color: rgba(255, 255, 255, 0.1);
      padding: 6px 12px;
      border-radius: 6px;
      display: inline-block;
      font-size: 1rem;
      margin-top: 0.5rem;
    }
    .btn-back {
      background-color: #ffffff;
      color: #000;
      font-weight: 500;
      border: none;
    }
    .btn-back:hover {
      background-color: #e2e2e2;
    }
  </style>
</head>
<body>

  <div class="container mt-5">
    <div class="alert alert-card">
      <h5 class="mb-3">✅ API Key Generated Successfully!</h5>
      <p class="mb-2">Your new API key is:</p>
      <code>' . htmlspecialchars($api_key) . '</code>
      <hr style="border-color: rgba(255,255,255,0.2);">
      <a href="index.php" class="btn btn-back mt-2">🔙 Back to API Keys</a>
    </div>
  </div>

</body>
</html>';

    
    exit;
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">🔐 Generate API Key</h5>
        </div>

        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label for="website_id" class="form-label">Select Website</label>
              <select name="website_id" id="website_id" class="form-select" required>
                <option value="">-- Choose a website --</option>
                <?php
                $webs = $conn->query("SELECT id, name FROM websites");
                while ($w = $webs->fetch_assoc()) {
                    echo "<option value='{$w['id']}'>" . htmlspecialchars($w['name']) . "</option>";
                }
                ?>
              </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Generate API Key</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

   
</div>
<?php include '../includes/footer.php'; ?>
