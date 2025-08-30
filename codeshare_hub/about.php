<?php
require 'db_connect.php';

// Procesăm formularul
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);
    $rating = (int)$_POST['rating'];

    if ($name && $message && $rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO feedback (name, message, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $message, $rating);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: about.php");
    exit();
}

// Luăm ultimele 10 feedback-uri
$feedbacks = mysqli_query($conn, "SELECT name, message, rating, created_at FROM feedback ORDER BY created_at DESC LIMIT 10");

// Construim HTML pentru feedback
$output = "";
while ($row = mysqli_fetch_assoc($feedbacks)) {
    $stars = "";
    for ($i = 1; $i <= 5; $i++) {
        $stars .= ($i <= $row['rating']) ? "⭐" : "☆";
    }

    $output .= '<div class="testimonial-card">';
    $output .= '<p>"' . htmlspecialchars($row['message']) . '"</p>';
    $output .= '<div class="stars">' . $stars . '</div>';
    $output .= '<h4>- ' . htmlspecialchars($row['name']) . ' (' . $row['created_at'] . ')</h4>';
    $output .= '</div>';
}

// Injectăm în about.html
$html = file_get_contents("about.html");
$html = str_replace('<div id="dynamic-feedback"></div>', $output, $html);

echo $html;
