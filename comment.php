<?php
session_start();
require_once "conn.php";
require "header_tweet.php";

// Controleer of er een post ID is ingestuurd
if (isset($_GET['post_id'])) {
    // Haal de post ID op uit de URL
    $post_id = $_GET['post_id'];

    // Verwerk het commentaarformulier
    if (isset($_POST['comment_text'])) {
        // Haal het commentaartekst op uit het formulier
        $comment_text = $_POST['comment_text'];

        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment_text, comment_date) VALUES (:post_id, :user_id, :comment_text, NOW())");
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':comment_text', $comment_text);
        $stmt->execute();

        $update_query = "UPDATE posts SET comment_count = (SELECT COUNT(*) FROM comments WHERE post_id = :post_id) WHERE post_id = :post_id";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':post_id', $post_id);
        $update_stmt->execute();

        header("location:all_posts.php");
        exit;
    }

    $query = "SELECT p.*, COUNT(l.like_id) AS like_count, r.username,
                      c.comment_text, c.comment_date, u.username AS comment_author
              FROM posts p
              LEFT JOIN likes l ON p.post_id = l.post_id
              JOIN registration r ON p.user_id = r.user_id
              LEFT JOIN comments c ON p.post_id = c.post_id
              LEFT JOIN registration u ON c.user_id = u.user_id
              WHERE p.post_id = :post_id
              GROUP BY p.post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<div class='tweet_box'>";
    echo "<div class='tweet_body'>";
    echo "<p><strong><big>@{$post['username']}</big></strong> <em>~{$post['post_date']}</em></p>";
    echo "<p>{$post['post_text']}</p>";

    // Afbeelding tonen (indien aanwezig)
    if (!empty($post['post_image'])) {
        echo "<div class='tweet_images'>";
        echo "<img class='image_tag' src='data:image/jpeg;base64," . base64_encode($post['post_image']) . "' alt='Posted Image'>";
        echo "</div>";
    }


    // Commentaarsectie
    echo "<div class='comment-section'>";
    echo "<form action='comment.php?post_id={$post['post_id']}' method='post'>";
    echo "<textarea name='comment_text' id='comment_text' placeholder='Plaats hier je reactie' required></textarea><br>";
    echo "<button class='comment_button' type='submit'>Plaats reactie</button>";
    echo "</form>";


    echo "</div>"; 

    echo "</div>"; 
    echo "</div>"; 
} else {
    echo "Geen geldige post ID.";
}
