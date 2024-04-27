<?php
require_once "conn.php";
require "header_tweet.php";

$query = "SELECT p.*, COUNT(l.like_id) AS like_count, r.username, r.firstName, r.lastName
          FROM posts p
          LEFT JOIN likes l ON p.post_id = l.post_id
          JOIN registration r ON p.user_id = r.user_id
          WHERE p.user_id= :user_id
          GROUP BY p.post_id
          ORDER BY p.post_id DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<?php foreach ($posts as $row) : ?>
    <div class="tweet_box">
        <div class="tweet_body">
            <p id="names-content"><strong><?php echo $row['firstName'] ?? 'Guest'; ?><?php echo $row['lastName'] ?? 'Guest'; ?></strong><em> @<?php echo $_SESSION['gebruikersnaam'] ?? 'Guest'; ?>~<?php echo $row['post_date']; ?></em></p>
            <p><?php echo $row['post_text']; ?></p>

            <!-- afbeeldingen tonen -->

            <?php if (!empty($row['post_image'])) : ?>
                <div class='tweet_images'>
                    <img class="image_tag" src='data:image/jpeg;base64,<?= base64_encode($row['post_image']) ?>' alt='Posted Image'>
                </div>
            <?php endif; ?>





            <!-- Like knop en teller -->
            <div class="like-section">
                <span class="like-btn" data-id="<?php echo $row['post_id']; ?>">
                    <span class="heart">&#10084;</span>
                </span>
                <span class="like-count"><?php echo $row['like_count']; ?> </span>
                <a href="comment.php?post_id=<?php echo $row['post_id']; ?>">&nbsp;<i class="fa fa-comment">&nbsp;</i></a> <?php echo $row['comment_count']; ?>

            </div>


            <!-- Extra acties -->
            <div class="tweet_icons-wrapper">
                <div class="tweet_icons-add">

                    <a style="color: red;" href="delete_post.php?post_id=<?php echo $row['post_id']; ?>">Delete</a>
                    <a style="color:darkorange;" href="edit_post.php?post_id=<?php echo $row['post_id']; ?>">Edit </a>
                    <p><br></p>
                </div>
            </div>






            <!-- Pijl om opmerkingen te tonen/verbergen -->
            <div class="comment-toggle" data-postid="<?php echo $row['post_id']; ?>" style="cursor: pointer;">▼ Show Comments</div>


            <!-- Toon de comments voor deze post -->
            <div class="comments-container" data-postid="<?php echo $row['post_id']; ?>" style="display: none;">
                <?php
                $comments_query = "SELECT c.*, r.username AS comment_author
                                   FROM comments c
                                   JOIN registration r ON c.user_id = r.user_id
                                   WHERE c.post_id = :post_id";
                $stmt = $conn->prepare($comments_query);
                $stmt->bindParam(':post_id', $row['post_id']);
                $stmt->execute();
                $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($comments as $comment) {
                ?>
                    <div class='comment'>
                        <p><strong>@<?= $comment['comment_author'] ?></strong> <em> ~ <?= $comment['comment_date'] ?></em></p>
                        <p><?= $comment['comment_text'] ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggleElements = document.querySelectorAll('.comment-toggle');
        toggleElements.forEach(function(element) {
            element.addEventListener('click', function() {
                var postId = this.getAttribute('data-postid');
                var commentsContainer = document.querySelector('.comments-container[data-postid="' + postId + '"]');
                commentsContainer.style.display = (commentsContainer.style.display === 'none') ? 'block' : 'none';
                this.textContent = (commentsContainer.style.display === 'none') ? '▼ Show Comments' : '▲ Hide Comments';
            });
        });
    });
</script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.like-btn').on('click', function() {
            var postId = $(this).data('id'); 
            $(this).toggleClass('clicked');

            $.ajax({
                url: 'like_post.php',
                type: 'post',
                data: {
                    post_id: postId
                },
                success: function(response) {
                    var responseData = JSON.parse(response); 
                    var likeBtn = $('[data-id="' + postId + '"]'); 

                    likeBtn.siblings('.like-count').text(responseData.like_count);

                    if (responseData.liked) {
                        likeBtn.addClass('liked');
                    } else {
                        likeBtn.removeClass('liked');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>