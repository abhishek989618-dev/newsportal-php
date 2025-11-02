<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Role check
$role_id = $_SESSION['role_id'];
if ($role_id != 1 && !has_permission($conn, $role_id, 'events', 'update')) {
  die("❌ Access Denied: You do not have permission to edit events.");
}

$id = (int) $_GET['id'];
$res = $conn->query("SELECT * FROM events WHERE id = $id");
$event = $res->fetch_assoc();
$selected_websites = json_decode($event['website_id'], true) ?? [];
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card shadow border-dark">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">✏️ Edit Event</h5>
      </div>
      <div class="card-body">
        <form method="post" action="update.php?id=<?= $id ?>" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" value="<?= htmlspecialchars($event['title']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"
              rows="4"><?= htmlspecialchars($event['description']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Event Date</label>
            <input type="date" name="event_date" class="form-control" value="<?= $event['event_date'] ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($event['location']) ?>"
              required>
          </div>

          <?php if (!empty($event['image'])): ?>
            <div class="mb-3">
              <label class="form-label">Current Image</label><br>
              <img src="../uploads/events/<?= $event['image'] ?>" width="100" class="rounded border mb-2">
            </div>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label">Change Image</label>
            <input type="file" name="image" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Select Website(s)</label>
            <select name="website_ids[]" class="form-select" multiple>
              <?php
              $webs = $conn->query("SELECT id, name FROM websites");
              while ($w = $webs->fetch_assoc()) {
                $sel = in_array($w['id'], $selected_websites) ? 'selected' : '';
                echo "<option value='{$w['id']}' $sel>" . htmlspecialchars($w['name']) . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label">Internal Notes</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($event['notes']) ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100">💾 Update Event</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>