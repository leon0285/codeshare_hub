<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit;
}

$user_id = $_SESSION['user_id'];

// Optional: check CSRF token if implemented
// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//   exit("CSRF validation failed.");
// }

// Sanitize input
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];

// Validate
if (empty($username) || empty($email)) {
  exit("Username and email cannot be empty.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  exit("Invalid email format.");
}

if (!empty($password)) {
  // Update username, email, and password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $hashed_password, $user_id);
} else {
  // Update username and email only
  $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $user_id);
}

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Redirect to profile page with success message
header("Location: profile.php?updated=1");
exit;
?>
