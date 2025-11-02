<?php
require '../session.php';
require '../config.php';

$ticket_id = (int)($_GET['id'] ?? 0);
$user_id   = $_SESSION['user_id'];
$role_id   = $_SESSION['role_id'];
$is_admin  = in_array($role_id, [1, 4]); // adjust for support roles

// Fetch ticket
$stmt = $conn->prepare("SELECT t.*, u.name AS reporter FROM tickets t JOIN users u ON u.id = t.user_id WHERE t.id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();
if (!$ticket) die("❌ Ticket not found");

// Permission check
if (!$is_admin && $ticket['user_id'] !== $user_id) {
    die("⛔ Access denied.");
}

// Fetch messages
$msg_stmt = $conn->prepare("SELECT m.*, u.name FROM ticket_messages m JOIN users u ON u.id = m.user_id WHERE m.ticket_id = ? ORDER BY m.created_at ASC");
$msg_stmt->bind_param("i", $ticket_id);
$msg_stmt->execute();
$messages = $msg_stmt->get_result();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="card border-dark shadow">
    <div class="card-header bg-dark text-white d-flex justify-content-between">
      <div>
        <h5 class="mb-0">🧾 Ticket #<?= $ticket['id'] ?> - <?= htmlspecialchars($ticket['subject']) ?></h5>
        <small><strong>Status:</strong> <?= ucfirst($ticket['status']) ?> | <strong>User:</strong> <?= htmlspecialchars($ticket['reporter']) ?></small>
      </div>
      <a href="index.php" class="btn btn-sm btn-light">← Back</a>
    </div>
    <div class="card-body" style="max-height: 500px; overflow-y: auto; background: #f9f9f9;">
      <?php if ($messages->num_rows > 0): ?>
        <?php while ($msg = $messages->fetch_assoc()): ?>
          <div class="mb-3 <?= $msg['user_id'] == $user_id ? 'text-end' : 'text-start' ?>">
            <div class="d-inline-block p-3 rounded <?= $msg['user_id'] == $user_id ? 'bg-primary text-white' : 'bg-light text-dark' ?>">
              <strong><?= htmlspecialchars($msg['name']) ?>:</strong><br>
              <?= nl2br(htmlspecialchars($msg['message'])) ?>
              <?php if (!empty($msg['attachment'])): ?>
                <br><a href="../uploads/messages/<?= htmlspecialchars($msg['attachment']) ?>" target="_blank">📎 Attachment</a>
              <?php endif; ?>
              <div class="small text-muted mt-1"><?= date('d M Y h:i A', strtotime($msg['created_at'])) ?></div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-muted">No messages yet.</p>
      <?php endif; ?>
    </div>
    <div class="card-footer bg-light">
      <form method="post" action="../messages/store.php" enctype="multipart/form-data" class="d-flex flex-column flex-md-row gap-2 align-items-end">
        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
        <textarea name="message" class="form-control" placeholder="Type your message..." rows="2" required></textarea>
        <input type="file" name="attachment" class="form-control" accept=".jpg,.png,.pdf,.docx,.zip">
        <button type="submit" class="btn btn-success">💬 Send</button>
      </form>
    </div>
  </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
