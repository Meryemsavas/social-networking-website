<?php
session_start();
require "nav.php";
require "conn.php";
require "header_tweet.php";

// Controleer of het formulier is verzonden
if (isset($_POST['btn_add_post'])) {
    // Haal de tekst op uit het formulier
    $post_text = isset($_POST['post_text']) ? $_POST['post_text'] : '';

    // Controleer of er afbeeldingen zijn geüpload
    if (!empty($_FILES['upload_image']['name'][0])) {
        // Haal de afbeeldingen op uit het formulier
        $post_images = $_FILES['upload_image'];

        // Loop door elke afbeelding en verwerk deze
        foreach ($post_images['tmp_name'] as $index => $tmp_name) {
            // Controleer of de afbeelding succesvol is geüpload
            if (!empty($tmp_name) && $post_images['error'][$index] === UPLOAD_ERR_OK) {
                // Lees de afbeeldingsgegevens
                $image_data = file_get_contents($tmp_name);

                // Voeg de afbeelding, tekst en gebruikers-id toe aan de database
                try {

                    $sql = "INSERT INTO posts (user_id, post_text, post_image, post_date) VALUES(:user_id, :post_text, :post_image, NOW())";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':post_text', $post_text);
                    $stmt->bindParam(':post_image', $image_data, PDO::PARAM_LOB);
                    $stmt->bindParam(':user_id', $_SESSION['user_id']); 
                    $stmt->execute();

                    // echo "<script>alert ('Post toegevoegd!')</script>";
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        }
    } else {
        // Voeg alleen de tekst toe aan de database
        try {


            $stmt = $conn->prepare("INSERT INTO posts (user_id, post_text, post_date) VALUES(:user_id, :post_text, NOW())");
            $stmt->bindParam(':post_text', $post_text);
            $stmt->bindParam(':user_id', $_SESSION['user_id']); 
            $stmt->execute();
            // echo "<script>alert ('Post toegevoegd!')</script>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>





<div class="grid-container">
    <div class="main">
        <p class="page_title">Home</p>

        <div class="tweet_box tweet_add" style="border: none;">
            <div class="tweet_left">
            </div>
            <div class="tweet_body">
                <form enctype="multipart/form-data" method="post" action="tweet_index.php">
                    <textarea name="post_text" id="post_text" cols="30" rows="3" placeholder="What's happening"></textarea>
                    <label for="upload_image_button" class="btn btn-warning" id="upload_image_button">Select image
                        <input type="file" name="upload_image[]" multiple accept="image/*">
                    </label>
                    <div class="tweet_icons-wrapper">
                        <div class="tweet_icons-add">
                            <!-- <i class="far fa-image"></i> -->
                            <!-- <button><i class="fa fa-heart"></i></button> -->
                            <!-- <button><i class="far fa-comment"></i></button> -->
                        </div>
                        <button class="button_tweet" type="submit" name="btn_add_post">Tweet</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div>

<?php require_once "tweet.php"; ?>