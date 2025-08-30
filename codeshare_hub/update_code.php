<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

// Validate and fetch POST data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = trim($_POST['title']);
    $language = trim($_POST['language']);
    $code = trim($_POST['code']);

    if (!$code_id || !$title || !$language || !$code) {
        die("All fields are required.");
    }

    // Update only if the code belongs to the user
    $stmt = $conn->prepare("UPDATE codes SET title=?, language=?, code=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sssii", $title, $language, $code, $code_id, $user_id);

    if ($stmt->execute()) {
        header("Location: my_posts.php?success=1");
        exit();
    } else {
        echo "Update failed.";
    }
} else {
    echo "Invalid request.";
}
?>
