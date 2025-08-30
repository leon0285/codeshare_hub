<?php
session_start();
require 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $username, $email);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="box">
    <div class="login">
      <div class="loginBx">
        <h2>Edit Profile</h2>
        <form action="update_profile.php" method="post">
          <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
          <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
          <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
          <input type="submit" value="Update Profile">
        </form>
        <br>
        <a href="dashboard.php">⬅️ Back to Dashboard</a>
      </div>
    </div>
  </div>

  <div class="box" style="margin-top: 30px;">
    <div class="login">
      <div class="loginBx">
        <h3>Your Shared Codes</h3>

        <?php
        $sql = "SELECT id, title, language, created_at FROM codes WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0): ?>
          <ul>
            <?php while ($code = mysqli_fetch_assoc($result)): ?>
              <li style="margin-bottom: 10px;">
                <strong><?php echo htmlspecialchars($code['title']); ?></strong> 
                (<?php echo $code['language']; ?>) - 
                <?php echo $code['created_at']; ?>
                [<a href="edit_code.php?id=<?php echo $code['id']; ?>">✏️ Edit</a>] 
                [<a href="delete_code.php?id=<?php echo $code['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">🗑️ Delete</a>]
              </li>
            <?php endwhile; ?>
          </ul>
        <?php else: ?>
          <p>You haven’t shared any code yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
