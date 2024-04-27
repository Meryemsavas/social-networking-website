<?php
require_once "conn.php";
session_start();

if (isset($_POST['post_id'], $_SESSION['user_id'])) {
  $postId = $_POST['post_id'];
  $userId = $_SESSION['user_id'];

  try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $likeCount = $stmt->fetchColumn();

    if ($likeCount > 0) {
      $deleteStmt = $conn->prepare("DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id");
      $deleteStmt->bindParam(':user_id', $userId);
      $deleteStmt->bindParam(':post_id', $postId);
      $deleteStmt->execute();
    } else {
      $insertStmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
      $insertStmt->bindParam(':user_id', $userId);
      $insertStmt->bindParam(':post_id', $postId);
      $insertStmt->execute();
    }

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id = :post_id");
    $countStmt->bindParam(':post_id', $postId);
    $countStmt->execute();
    $newLikeCount = $countStmt->fetchColumn();

    $updateStmt = $conn->prepare("UPDATE posts SET like_count = (SELECT COUNT(*) FROM likes WHERE post_id = :post_id) WHERE post_id = :post_id");
    $updateStmt->bindParam(':post_id', $postId);
    $updateStmt->execute();


    echo json_encode(array('like_count' => $newLikeCount, 'liked' => $likeCount == 0));
  } catch (PDOException $e) {
    echo json_encode(array('error' => 'Database error: ' . $e->getMessage()));
  }
} else {
  echo json_encode(array('error' => 'Required POST parameters missing'));
}
