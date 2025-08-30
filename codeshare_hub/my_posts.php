<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Home Login</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    <?php if (!$isLoggedIn): ?>
    /* Disable scrolling and hide everything but login form */
    body {
      overflow: hidden;
    }

    .header, .title, .back-to-top {
      display: none;
    }
    <?php endif; ?>
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <div class="header">
  <nav>
    <a href="main.html" class="nav-link">Home</a>
    <a href="about.php" class="nav-link">About</a>
    <a href="product.php" class="nav-link">Product</a>
    <a href="contact.html" class="nav-link">Contact</a>
    <a href="view_codes.html" class="nav-link">View Code</a>
    <a href="post_code.html" class="nav-link">Posting</a>
    <a href="main.html" class="nav-link logout-link">Log Out</a>
    <span></span>
  </nav>
</div>

  <!-- Login Box -->
  <div class="box">
    <div class="login">
      <div class="loginBx">
        <h2><i class="fa-solid fa-right-to-bracket"></i> Login <i class="fa-solid fa-heart"></i></h2>

        <form action="login.php" method="POST">
          <input type="text" name="username" placeholder="Username" required>
          <input type="password" name="password" placeholder="Password" required>
          <input type="submit" value="Sign in">

          <div class="form-options">
            <label><input type="checkbox" name="remember"> Remember me</label>
            <a href="#">Forgot Password?</a>
            <a href="register.html" class="register-btn">Register</a>
          </div>
        </form>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
          <p style="color: green; text-align:center; margin-top: 10px;">✅ Registration successful! You can now log in.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Back to Top Button -->
  <a href="#" class="back-to-top"><i class="fa-solid fa-arrow-up"></i></a>

  <!-- JS -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;

      // Disable nav click for unauthenticated users (extra layer)
      if (!isLoggedIn) {
        document.querySelectorAll('a.protected').forEach(link => {
          link.addEventListener('click', function (e) {
            e.preventDefault();
            alert("Please log in to access this page.");
          });
        });
      }

      // Back to top button
      const backToTop = document.querySelector('.back-to-top');
      window.addEventListener('scroll', () => {
        backToTop.style.display = window.scrollY > 200 ? 'block' : 'none';
      });
    });
  </script>
</body>
</html>
