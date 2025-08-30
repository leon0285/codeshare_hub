<?php
session_start();
require 'db_connect.php';

// dacă userul nu e logat → trimite la login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $language = trim($_POST['language']);
    $code = trim($_POST['code']);
    $user_id = $_SESSION['user_id'];

    // verificăm să nu fie gol
    if (empty($title) || empty($language) || empty($code)) {
        header("Location: post_code.html");
        exit();
    }

    // upload fișier (dacă există)
    $filename = "";
    if (!empty($_FILES["file"]["name"])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES["file"]["name"]);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // succes upload
        } else {
            $filename = ""; // fallback dacă nu s-a urcat
        }
    }

    // inserăm în baza de date
    $sql = "INSERT INTO posts (user_id, title, language, code, image, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $title, $language, $code, $filename);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: main.php"); // după succes, du-l pe feed
        exit();
    } else {
        echo "❌ Eroare la salvare: " . mysqli_error($conn);
    }
}
?>
