<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// ✅ Check permission to update tags
if (!has_permission($conn, $_SESSION['role_id'], 'tags', 'update')) {
    die("❌ Access Denied: You do not have permission to edit tags.");
}

// ✅ Sanitize tag ID
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Invalid tag ID.");
}

// ✅ Fetch tag
$res = $conn->query("SELECT * FROM tags WHERE id = $id");
if ($res->num_rows === 0) {
    die("❌ Tag not found.");
}
$tag = $res->fetch_assoc();

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name) {
        $stmt = $conn->prepare("UPDATE tags SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>⚠️ Tag name cannot be empty.</p>";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
    <?php include '../includes/navbar.php'?>
    <div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">✏️ Edit Tag</h5>
        </div>

        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label for="name" class="form-label">Tag Name</label>
              <input type="text"
                     name="name"
                     id="name"
                     class="form-control"
                     value="<?= htmlspecialchars($tag['name']) ?>"
                     required
                     placeholder="Enter tag name">
            </div>
            <button type="submit" class="btn btn-primary w-100">💾 Update Tag</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<?php include '../includes/footer.php'; ?>