<?php
require_once "conn.php";

if (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    try {
        $sql = "DELETE FROM comments WHERE post_id = :post_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();

        $sql = "DELETE FROM likes WHERE post_id = :post_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();

        $sql = "DELETE FROM posts WHERE post_id = :post_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id);

        if ($stmt->execute()) {
            header("Location: tweet_index.php");
            exit;
        } else {
            echo "Er is een fout opgetreden bij het verwijderen van de post.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Geen post_id gevonden om te verwijderen.";
}
