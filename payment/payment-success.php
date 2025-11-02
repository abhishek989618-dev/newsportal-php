<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Success</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.5/lottie.min.js"></script>
  <style>
    body {
      background: #f0f9ff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      overflow: hidden;
    }
    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      text-align: center;
      padding: 2rem;
      background-color: #fff;
    }
    .celebration {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      pointer-events: none;
    }
    .card-content {
      position: relative;
      z-index: 1;
    }
  </style>
</head>
<body>

<div class="celebration" id="lottie-animation"></div>

<div class="card">
  <div class="card-content">
    <h1 class="display-5 text-success mb-3">✅ Payment Successful!</h1>
    <p class="lead">Thank you for your purchase. Your order has been confirmed.</p>
    <a href="../orders/index.php" class="btn btn-primary mt-4">Go to Home</a>
  </div>
</div>

<script>
  const animation = lottie.loadAnimation({
    container: document.getElementById('lottie-animation'),
    renderer: 'svg',
    loop: false,
    autoplay: true,
    path: 'https://assets1.lottiefiles.com/packages/lf20_JUr2Xt.json' // Confetti animation
  });
</script>

</body>
</html>
