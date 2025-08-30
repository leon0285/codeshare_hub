<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = trim($_POST['fullname']);
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];

  // validări
  if (empty($fullname) || empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword)) {
    header("Location: register.html"); 
    exit;
  }

  if ($password !== $confirmPassword) {
    header("Location: register.html"); 
    exit;
  }

  // verifică dacă există user/email
  $checkSql = "SELECT id FROM users WHERE username = ? OR email = ?";
  $checkStmt = mysqli_prepare($conn, $checkSql);
  mysqli_stmt_bind_param($checkStmt, "ss", $username, $email);
  mysqli_stmt_execute($checkStmt);
  mysqli_stmt_store_result($checkStmt);

  if (mysqli_stmt_num_rows($checkStmt) > 0) {
    header("Location: register.html"); 
    exit;
  }
  mysqli_stmt_close($checkStmt);

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $insertSql = "INSERT INTO users (fullname, username, email, phone, password) VALUES (?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $insertSql);
  mysqli_stmt_bind_param($stmt, "sssss", $fullname, $username, $email, $phone, $hashedPassword);

  if (mysqli_stmt_execute($stmt)) {
    // succes → du-l la login
    header("Location: index.php");
    exit;
  } else {
    header("Location: register.html");
    exit;
  }
} else {
  header("Location: register.html");
  exit;
}
?>
