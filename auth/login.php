<?php
session_start();
require '../config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if (!password_verify($pass, $user['password'])) {
            $error = "❌ Invalid password.";
        } elseif ((int)$user['is_blocked'] === 1) {
            $error = "🚫 Your account is blocked by admin.";
        } elseif ((int)$user['is_verified'] !== 1) {
            $error = "🔐 Your account is not verified yet. Please wait for verification.";
        } else {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            header("Location: ../index.php");
            exit;
        }
    } else {
        $error = "❌ No account found with this email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(ellipse at top, #1a1a3d, #0f0f2f);
      color: #fff;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-card {
      background-color: #1e1e3f;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 25px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 400px;
    }
    .login-card input {
      background-color: #262642;
      border: 1px solid #444;
      color: #fff;
    }
    .login-card input::placeholder {
      color: #bbb;
    }
    .btn-login {
      background: linear-gradient(to right, #6f00ff, #00c3ff);
      border: none;
      color: #fff;
      font-weight: 600;
    }
    .error-msg {
      color: #ff6b6b;
      margin-top: 15px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="login-card">
  <h4 class="mb-4 text-center">🚀 Admin Login</h4>
  <form method="post" novalidate>
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="📧 Email" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="🔒 Password" required>
    </div>
    <button type="submit" class="btn btn-login w-100">Login</button>
  </form>

  <?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  Don't have account? <a href="/news-portal/auth/register.php">Register Here</a>
</div>

</body>
</html>
