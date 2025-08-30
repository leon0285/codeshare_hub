<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Product - CodeShare Hub</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

  <!-- Navigation -->
  <div class="header">
    <nav>
      <a href="about.php" class="nav-link">About</a>
      <a href="product.php" class="nav-link">Product</a>
      <a href="contact.html" class="nav-link">Contact</a>
      <a href="view_codes.php" class="nav-link">View Code</a>
      <a href="post_code.html" class="nav-link">Posting</a>
      <a href="main.html" class="nav-link logout-link">Log Out</a>
    </nav>
  </div>

  <div class="main-container">

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-text">
        <h1>🚀 CodeShare Hub</h1>
        <p>The place where developers learn, share, and grow together.</p>
        <a href="register.html" class="cta-button">Get Started</a>
      </div>
      <div class="hero-image">
        <img src="images/coding-hero.jpg" alt="CodeShare Hub Preview">
      </div>
    </section>

    <!-- Features -->
    <h2>✨ Features</h2>
    <div class="features-grid">
      <div class="feature-card">
        <i class="fa-solid fa-code"></i>
        <h3>Share Code Snippets</h3>
        <p>Upload and share your favorite code examples with the community.</p>
      </div>
      <div class="feature-card">
        <i class="fa-solid fa-image"></i>
        <h3>Upload Images & Files</h3>
        <p>Attach screenshots or project files to explain your code better.</p>
      </div>
      <div class="feature-card">
        <i class="fa-solid fa-heart"></i>
        <h3>Like & Comment</h3>
        <p>Engage with others by liking and commenting on their posts.</p>
      </div>
      <div class="feature-card">
        <i class="fa-solid fa-user-pen"></i>
        <h3>Manage Your Posts</h3>
        <p>Edit or delete your submissions anytime you want.</p>
      </div>
    </div>

    <!-- Why Choose Us -->
    <h2>💡 Why Choose CodeShare Hub?</h2>
    <div class="box">
      <p>
        Unlike generic forums, CodeShare Hub is designed for quick and easy 
        sharing of real code snippets, with built-in support for images, files, 
        and a friendly feedback system. Whether you're a student or a pro, 
        you'll find a place to learn and contribute.
      </p>
    </div>

    <!-- Call To Action -->
    <div class="cta-section">
      <h2>🎯 Ready to Share Your Code?</h2>
      <a href="register.html" class="cta-button">Join Now</a>
    </div>

  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <p>&copy; 2025 CodeShare Hub. All rights reserved.</p>
    <div class="social-icons">
      <a href="#"><i class="fab fa-facebook"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </footer>

</body>
</html>
