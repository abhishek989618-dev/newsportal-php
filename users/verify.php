<?php
require '../session.php';
require '../config.php';
require '../check_permission.php';

// Manually include PHPMailer
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Load environment variables from the parent directory (adjust path if needed)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


if (!has_permission($conn, $_SESSION['role_id'], 'users', 'update')) {
    die("❌ Access Denied: You do not have permission to verify users.");
}

// Validate ID
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("❌ User not found.");
}

$user = $res->fetch_assoc();
$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['method'] ?? 'self';

    switch ($method) {
        case 'email':
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
$mail->Host       = $_ENV['MAIL_HOST'];
$mail->SMTPAuth   = true;
$mail->Username   = $_ENV['MAIL_USERNAME'];
$mail->Password   = $_ENV['MAIL_PASSWORD'];
$mail->SMTPSecure = 'tls';
$mail->Port       = $_ENV['MAIL_PORT'];

$mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
$mail->addAddress($user['email'], $user['name']);


                $mail->isHTML(true);
                $mail->Subject = '✅ Account Verified';
                $mail->Body = "
                    <p>Hello <strong>" . htmlspecialchars($user['name']) . "</strong>,</p>
                    <p>Your account has been verified successfully.</p>
                    <p>Regards,<br>News Portal Admin</p>
                ";

                $mail->send();
                $verified = true;
                $alert = "✅ Email sent and user marked verified.";
            } catch (Exception $e) {
                $alert = "❌ Email failed: {$mail->ErrorInfo}";
                $verified = false;
            }
            break;

        case 'phone':
            $sms_sent = fake_send_sms($user['name'], $user['email']);
            if ($sms_sent) {
                $verified = true;
                $alert = "✅ SMS sent and user marked verified.";
            } else {
                $alert = "❌ Failed to send SMS.";
                $verified = false;
            }
            break;

        case 'self':
            $verified = true;
            $alert = "✅ User manually marked verified.";
            break;

        default:
            $verified = false;
            $alert = "❌ Invalid method.";
    }

    if ($verified) {
        $update = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
        $update->bind_param("i", $id);
        $update->execute();
    }
}

function fake_send_sms($name, $email) {
    // Simulate SMS
    return true;
}
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
  <div class="card shadow border-dark">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0">✅ Verify User: <?= htmlspecialchars($user['name']) ?></h5>
    </div>
    <div class="card-body">

      <?php if ($alert): ?>
        <div class="alert alert-info"><?= $alert ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label">Select Verification Method</label>
          <select name="method" class="form-select" required>
            <option value="email">📧 By Email</option>
            <option value="phone">📱 By Phone</option>
            <option value="self">🧾 Manual / Self Verified</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success w-100">✅ Confirm Verification</button>
      </form>
    </div>
  </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
 