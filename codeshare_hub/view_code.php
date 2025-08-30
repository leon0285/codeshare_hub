<?php
session_start();
require 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;
$filter = $_GET['lang'] ?? '';
$search = $_GET['search'] ?? '';

function escape($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if ($filter && $search) {
  $sql = "SELECT codes.id, codes.user_id, codes.title, codes.language, codes.code, codes.file_path, codes.created_at, users.username
          FROM codes
          JOIN users ON codes.user_id = users.id
          WHERE codes.language = ? AND (codes.title LIKE ? OR codes.code LIKE ?)
          ORDER BY codes.created_at DESC";
  $stmt = mysqli_prepare($conn, $sql);
  $searchTerm = "%$search%";
  mysqli_stmt_bind_param($stmt, "sss", $filter, $searchTerm, $searchTerm);
} elseif ($search) {
  $sql = "SELECT codes.id, codes.user_id, codes.title, codes.language, codes.code, codes.file_path, codes.created_at, users.username
          FROM codes
          JOIN users ON codes.user_id = users.id
          WHERE codes.title LIKE ? OR codes.code LIKE ?
          ORDER BY codes.created_at DESC";
  $stmt = mysqli_prepare($conn, $sql);
  $searchTerm = "%$search%";
  mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
} else {
  $sql = "SELECT codes.id, codes.user_id, codes.title, codes.language, codes.code, codes.file_path, codes.created_at, users.username
          FROM codes
          JOIN users ON codes.user_id = users.id
          ORDER BY codes.created_at DESC";
  $stmt = mysqli_prepare($conn, $sql);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Code Snippets</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="header">
  <nav>
    <a href="about.php" class="nav-link">About</a>
    <a href="product.php" class="nav-link">Product</a>
    <a href="contact.html" class="nav-link">Contact</a>
    <a href="view_codes.php" class="nav-link">View Code</a>
    <a href="post_code.php" class="nav-link">Posting</a>
    <a href="main.html" class="nav-link logout-link">Log Out</a>
    <span></span>
  </nav>
</div>

<div class="main-container">
  <h2>📂 Code Snippets</h2>
  <form method="GET" style="margin-bottom: 1em;">
    <input type="text" name="search" placeholder="Search..." value="<?= escape($search) ?>">
    <select name="lang">
      <option value="">All Languages</option>
      <option value="PHP" <?= $filter === 'PHP' ? 'selected' : '' ?>>PHP</option>
      <option value="JavaScript" <?= $filter === 'JavaScript' ? 'selected' : '' ?>>JavaScript</option>
      <option value="Python" <?= $filter === 'Python' ? 'selected' : '' ?>>Python</option>
    </select>
    <button type="submit">🔍</button>
  </form>

  <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="code-block">
      <h3><?= escape($row['title']) ?> (<?= escape($row['language']) ?>)</h3>
      <p>By: <strong><?= escape($row['username']) ?></strong> on <?= $row['created_at'] ?></p>
      <pre><?= escape($row['code']) ?></pre>

      <?php
        $file_path = $row['file_path'];
        if ($file_path):
          $ext = pathinfo($file_path, PATHINFO_EXTENSION);
          $is_image = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
      ?>
        <div class="uploaded-file" style="margin: 10px 0;">
          <?php if ($is_image): ?>
            <p><strong>📷 Uploaded Image:</strong></p>
            <img src="<?= escape($file_path) ?>" alt="Uploaded Image" style="max-width: 300px; max-height: 200px; border: 1px solid #ccc; border-radius: 5px;" />
          <?php else: ?>
            <p><strong>📄 Attached File:</strong> <a href="<?= escape($file_path) ?>" target="_blank">Download/View</a></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php
        $like_count = 0;
        $like_stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE code_id = ?");
        $like_stmt->bind_param("i", $row['id']);
        $like_stmt->execute();
        $like_stmt->bind_result($like_count);
        $like_stmt->fetch();
        $like_stmt->close();
      ?>

      <form action="like_comment.php" method="POST" style="margin-top: 10px;">
        <input type="hidden" name="code_id" value="<?= $row['id'] ?>">
        <button type="submit" name="like">❤️ Like (<?= $like_count ?>)</button>
        <?php if ($user_id): ?>
          <br>
          <textarea name="comment" placeholder="Add a comment..." rows="2" cols="50"></textarea><br>
          <button type="submit">💬 Submit</button>
        <?php endif; ?>
      </form>

      <h4>Comments:</h4>
      <?php
        $comment_stmt = $conn->prepare("SELECT comments.comment, users.username, comments.created_at
                                        FROM comments JOIN users ON comments.user_id = users.id
                                        WHERE comments.code_id = ? ORDER BY comments.created_at DESC");
        $comment_stmt->bind_param("i", $row['id']);
        $comment_stmt->execute();
        $comments = $comment_stmt->get_result();
        while ($c = $comments->fetch_assoc()):
      ?>
        <p><strong><?= escape($c['username']) ?>:</strong> <?= escape($c['comment']) ?> <em>(<?= $c['created_at'] ?>)</em></p>
      <?php endwhile;
      $comment_stmt->close(); ?>

      <?php if ($user_id && $user_id == $row['user_id']): ?>
        <form action="edit_code.php" method="GET" style="display:inline-block; margin-top:10px;">
          <input type="hidden" name="code_id" value="<?= $row['id'] ?>">
          <button type="submit">✏️ Edit</button>
        </form>

        <form action="delete_code.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" style="display:inline-block; margin-left:10px;">
          <input type="hidden" name="code_id" value="<?= $row['id'] ?>">
          <button type="submit" style="color:red;">🗑️ Delete</button>
        </form>
      <?php endif; ?>
      <hr>
    </div>
  <?php endwhile; ?>
</div>
</body>
</html>
