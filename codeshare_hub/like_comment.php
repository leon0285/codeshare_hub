<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  exit("❌ Unauthorized. Please log in.");
}

$user_id = $_SESSION['user_id'];
$code_id = $_POST['code_id'] ?? null;
$comment = trim($_POST['comment'] ?? '');

if (!$code_id || !is_numeric($code_id)) {
  exit("❌ Invalid code ID.");
}

// ❤️ Handle like
if (isset($_POST['like'])) {
  // Check if already liked
  $check = mysqli_prepare($conn, "SELECT id FROM likes WHERE user_id = ? AND code_id = ?");
  mysqli_stmt_bind_param($check, "ii", $user_id, $code_id);
  mysqli_stmt_execute($check);
  mysqli_stmt_store_result($check);

  if (mysqli_stmt_num_rows($check) == 0) {
    $like_sql = mysqli_prepare($conn, "INSERT INTO likes (user_id, code_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($like_sql, "ii", $user_id, $code_id);
    mysqli_stmt_execute($like_sql);
    mysqli_stmt_close($like_sql);
  }

  mysqli_stmt_close($check);
}

// 💬 Handle comment
if (!empty($comment)) {
  $comment_sql = mysqli_prepare($conn, "INSERT INTO comments (user_id, code_id, comment, created_at) VALUES (?, ?, ?, NOW())");
  mysqli_stmt_bind_param($comment_sql, "iis", $user_id, $code_id, $comment);
  mysqli_stmt_execute($comment_sql);
  mysqli_stmt_close($comment_sql);
}

header("Location: view_code.php");
exit;
?>

