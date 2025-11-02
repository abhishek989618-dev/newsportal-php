<?php
session_start();
require_once '../config.php'; // make sure $conn is available

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$user_id = $_SESSION['user_id'];
$attachment = null;
$errors = [];

// Validate inputs
if (empty($subject)) {
    $errors[] = "Subject is required.";
}
if (empty($message)) {
    $errors[] = "Message is required.";
}

// Handle file upload if present
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/tickets/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp = $_FILES['attachment']['tmp_name'];
    $fileName = time() . '_' . basename($_FILES['attachment']['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmp, $filePath)) {
        $attachment = $fileName;
    } else {
        $errors[] = "Failed to upload the attachment.";
    }
}

if (empty($errors)) {
    $stmt = $conn->prepare("INSERT INTO tickets (user_id, subject, message, attachment, status) VALUES (?, ?, ?, ?, 'open')");
    $stmt->bind_param("isss", $user_id, $subject, $message, $attachment);

    if ($stmt->execute()) {
        header("Location: index.php?created=1");
        exit;
    } else {
        $errors[] = "Database error: " . $conn->error;
    }
}

// If there are errors, show them
if (!empty($errors)) {
    echo "<div style='background: #1a1a1a; color: #f66; padding: 20px;'>";
    echo "<h3>❌ Ticket Creation Failed:</h3><ul>";
    foreach ($errors as $e) {
        echo "<li>" . htmlspecialchars($e) . "</li>";
    }
    echo "</ul><a href='create.php'>⬅️ Go Back</a></div>";
}
?>
