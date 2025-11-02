<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Allow only users with 'create' permission or superadmin (role_id == 1)
$role_id = $_SESSION['role_id'];
if ($role_id != 1 && !has_permission($conn, $role_id, 'events', 'create')) {
    die("❌ Access Denied: You do not have permission to create events.");
}
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">📅 Add New Event</h5>
      </div>
      <div class="card-body">
        <form method="post" action="store.php" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Event Title</label>
            <input name="title" type="text" class="form-control" placeholder="Enter event title" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Event description"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Event Date</label>
            <input type="date" name="event_date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" placeholder="Enter event location" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Select Website(s)</label>
            <select name="website_ids[]" class="form-select" multiple required>
              <?php
              $websites = $conn->query("SELECT id, name FROM websites");
              while ($w = $websites->fetch_assoc()) {
                  echo "<option value='{$w['id']}'>" . htmlspecialchars($w['name']) . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
          <label class="form-label">Internal Notes</label>
          <textarea name="notes" class="form-control" placeholder="Optional internal notes"></textarea>
        </div>

          <button type="submit" class="btn btn-primary w-100">💾 Save Event</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
