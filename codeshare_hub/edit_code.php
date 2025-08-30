<?php
session_start();
require_once 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$code_id = isset($_GET['code_id']) ? intval($_GET['code_id']) : 0;
$error = "";

// Fetch the post
$stmt = $conn->prepare("SELECT * FROM codes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $code_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$codeData = $result->fetch_assoc();

if (!$codeData) {
    echo "❌ Code not found or access denied.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $language = trim($_POST['language']);
    $code = trim($_POST['code']);

    if ($title && $language && $code) {
        $updateStmt = $conn->prepare("UPDATE codes SET title=?, language=?, code=? WHERE id=? AND user_id=?");
        $updateStmt->bind_param("sssii", $title, $language, $code, $code_id, $user_id);
        if ($updateStmt->execute()) {
            header("Location: view_code.php");
            exit();
        } else {
            $error = "❌ Failed to update code. Try again.";
        }
    } else {
        $error = "⚠️ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Code</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="box">
    <div class="login">
      <div class="loginBx">
        <h2>Edit Your Code</h2>
        <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="POST">
          <input type="text" name="title" value="<?= htmlspecialchars($codeData['title']) ?>" required>
          <select name="language" required>
            <option value="HTML" <?= $codeData['language'] === 'HTML' ? 'selected' : '' ?>>HTML</option>
            <option value="CSS" <?= $codeData['language'] === 'CSS' ? 'selected' : '' ?>>CSS</option>
            <option value="JavaScript" <?= $codeData['language'] === 'JavaScript' ? 'selected' : '' ?>>JavaScript</option>
            <option value="PHP" <?= $codeData['language'] === 'PHP' ? 'selected' : '' ?>>PHP</option>
          </select>
          <textarea name="code" rows="10" required><?= htmlspecialchars($codeData['code']) ?></textarea>
          <input type="submit" value="Update Code">
        </form>
        <br>
        <a href="view_code.php">⬅️ Back to All Code</a>
      </div>
    </div>
  </div>
</body>
</html>
