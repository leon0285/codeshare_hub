<?php
session_start();
require 'db_connect.php';

// Încărcăm datele pentru landing din DB (opțional)
$query = "SELECT title, subtitle, description FROM homepage LIMIT 1";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $data['title']; ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <section class="content">
    <div class="image-container">
      <img src="images/pc-setup.jpg" alt="Coding Setup">
    </div>

    <div class="text-container">
      <h2>🚀 <?php echo $data['subtitle']; ?></h2>
      <p><?php echo nl2br($data['description']); ?></p>
      <a href="login.php" class="btn">Get Started</a>
    </div>
  </section>

</body>
</html>
