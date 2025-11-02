<?php
require '../config.php';
$alert = "";

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id  = 7; // Enduser
    $is_verified = 0;
    $is_blocked  = 0;

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $alert = "❌ Email already registered.";
    } else {
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role_id, is_verified, is_blocked) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $name, $email, $password, $role_id, $is_verified, $is_blocked);
        $stmt->execute();
        $user_id = $stmt->insert_id;

        // Generate reporter_id
        $reporter_id = "reporter_" . $user_id;
        $update = $conn->prepare("UPDATE users SET reporter_id = ? WHERE id = ?");
        $update->bind_param("si", $reporter_id, $user_id);
        $update->execute();

        // 🔔 Notify Admins and Super Admins
        $adminQuery = $conn->query("SELECT id, role_id FROM users WHERE role_id IN (1, 2)");

        $targetUserIds = [];
        $notification_role_id = null;

        while ($row = $adminQuery->fetch_assoc()) {
            $targetUserIds[] = (int)$row['id'];
            if (!$notification_role_id) {
                $notification_role_id = (int)$row['role_id']; // take first role found (e.g., 1 or 2)
            }
        }

        // Only insert notification if at least 1 admin/superadmin found
        if (!empty($targetUserIds)) {
            $title = "🆕 New Reporter Registration";
            $type = 1;
            $message = "$name ($email) has registered and is awaiting verification.";
            $json_target_users = json_encode($targetUserIds);
            $is_read = 0;

            $notif = $conn->prepare("INSERT INTO notifications 
                (title, type, message, user_id, role_id, target_users, is_read, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $notif->bind_param("sssiiis", $title, $type, $message, $user_id, $notification_role_id, $json_target_users, $is_read);
            $notif->execute();
        }

        $alert = "✅ Registration complete. Awaiting verification by Admin.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap + Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background: radial-gradient(ellipse at top, #1a1a3d, #0f0f2f);
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .register-card {
      background-color: #1f1f3e;
      border-radius: 10px;
      padding: 2rem;
      width: 100%;
      max-width: 500px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    }

    .form-control, .form-select {
      background-color: #292945;
      border: 1px solid #444;
      color: #fff;
    }

    .form-control::placeholder {
      color: #bbb;
    }

    .btn-register {
      background: linear-gradient(to right, #00c3ff, #6f00ff);
      border: none;
      color: #fff;
      font-weight: bold;
    }

    .alert-custom {
      background-color: #1a1a1a;
      border-left: 5px solid #00c3ff;
      color: #ccc;
    }
  </style>
</head>
<body>

<div class="register-card">
  <h4 class="mb-4 text-center">🧑‍🚀 Register New User</h4>

  <?php if (!empty($alert)): ?>
    <div class="alert alert-custom p-3 mb-3">
      <?= htmlspecialchars($alert) ?>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <input type="text" name="name" class="form-control" placeholder="👤 Full Name" required>
    </div>
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="📧 Email" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="🔒 Password" required>
    </div>
    <button type="submit" class="btn btn-register w-100">🚀 Register</button>
  </form>
</div>

</body>
</html>
