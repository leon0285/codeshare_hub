<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: login.html");
        exit();
    }

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // login reușit → mergi la main.html
        header("Location: main.html");
        exit();
    } else {
        // login eșuat → înapoi la login.html
        header("Location: login.html");
        exit();
    }
}
else {
    header("Location: login.html");
    exit();
}
