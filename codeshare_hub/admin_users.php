<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'db_connect.php';

// Must be logged in and be admin
if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit;
}

// Check if current user is admin
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$is_admin = $result->fetch_assoc()['is_admin'];
$stmt->close();

if (!$is_admin) {
  exit("❌ Access denied. Admins only.");
}

// Fetch all users
$users = $conn->query("SELECT id, fullname, username, email, is_admin FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Users</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="header">
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="admin_users.php" class="active">Manage Users</a>
      <a href="logout.php">Logout</a>
    </nav>
  </div>

  <div class="box">
    <div class="loginBx">
      <h2>👑 Admin Panel - Users</h2>
      <table border="1">
        <tr>
          <th>ID</th><th>Full Name</th><th>Username</th><th>Email</th><th>Admin</th>
        </tr>
        <?php while ($row = $users->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['is_admin'] ? 'Yes' : 'No' ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </div>
</body>
</html>
