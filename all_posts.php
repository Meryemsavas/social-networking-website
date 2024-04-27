<?php
session_start();
require "header_tweet.php";
require "nav.php";
require "conn.php";

$query = "SELECT 
    posts.*, 
    registration.username,
    registration.firstName,
    registration.lastName, 
    (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.post_id) AS like_count,
    (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.post_id) AS comment_count
FROM 
    posts 
INNER JOIN 
    registration ON posts.user_id = registration.user_id
ORDER BY 
    posts.post_id DESC;";
$stmt = $conn->prepare($query);

$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<?php foreach ($posts as $row) : ?>
    <div class="tweet_box">
        <div class="tweet_body">
            <p id="names_content"><strong><?php echo $row['firstName']; ?> <?php echo $row['lastName']; ?></strong><em>&nbsp;@<?php echo $row['username']; ?> ~ <?php echo $row['post_date']; ?></em></p>
            <p><?php echo $row['post_text']; ?></p>

            <!-- afbeeldingen tonen -->
            <?php if (!empty($row['post_image'])) : ?>
                <div class='tweet_images'>
                    <img class="image_tag" src='data:image/jpeg;base64,<?= base64_encode($row['post_image']) ?>' alt='Posted Image'>
                </div>
            <?php endif; ?>

            <!-- Like knop en teller -->
            <div class="like-section">
                <span class="like-btn <?php echo isset($row['liked']) && $row['liked'] ? 'clicked' : ''; ?>" data-id="<?php echo $row['post_id']; ?>">
                    <span class="heart">&#10084;</span>
                </span>
                <span class="like-count"><?php echo $row['like_count']; ?></span>
                <a href="comment.php?post_id=<?php echo $row['post_id']; ?> ">&nbsp;<i class="fa fa-comment">&nbsp;</i></a> <?php echo $row['comment_count']; ?>


            </div>

            <!-- Extra acties -->
            <div class="tweet_icons-wrapper">
                <div class="tweet_icons-add">
                </div>
            </div>

            <!-- Pijl om opmerkingen te tonen/verbergen -->
            <div class="comment-toggle" data-postid="<?php echo $row['post_id']; ?>" style="cursor: pointer;" >▼ Show Comments</div>


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
                        <p><strong>@<?= $comment['comment_author'] ?></strong> - <?= $comment['comment_date'] ?></p>
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

            // Maak een AJAX-verzoek om de like-actie te verwerken
            $.ajax({
                url: 'like_post.php',
                type: 'post',
                data: {
                    post_id: postId
                },
                success: function(response) {
                    // Verwerk het antwoord en update de like-knop en teller
                    var responseData = JSON.parse(response); // Parseer het antwoord naar een JavaScript-object
                    var likeBtn = $('[data-id="' + postId + '"]'); // Selecteer de like-knop met de juiste post-ID

                    // Update de like-teller
                    likeBtn.siblings('.like-count').text(responseData.like_count);

                    // Pas de stijl van de like-knop aan op basis van de like-status
                    if (responseData.liked) {
                        // Gebruiker heeft de post geliket
                        likeBtn.addClass('liked');
                    } else {
                        // Gebruiker heeft de like verwijderd
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