<?php
session_start();
require 'db_connect.php';

// Check request method, user login, and required POST param
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id']) || !isset($_POST['code_id'])) {
  header("Location: index.html");
  exit;
}

$user_id = $_SESSION['user_id'];
$code_id = intval($_POST['code_id']);

// Attempt to delete the code entry
$sql = "DELETE FROM codes WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $code_id, $user_id);
$stmt->execute();

// If nothing was deleted
if ($stmt->affected_rows === 0) {
  echo "❌ Code not found or permission denied.";
  exit;
}

$stmt->close();
header("Location: view_code.php");
exit;
?>
