<?php
require_once "conn.php";

// Controleer of een post_id is ontvangen via GET-parameters
if (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Controleer of het bewerkingsformulier is verzonden (POST-methode)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Haal de gegevens op uit het bewerkingsformulier
        $post_text = isset($_POST['post_text']) ? $_POST['post_text'] : '';
        $post_image = isset($_FILES['upload_image']['name']) ? $_FILES['upload_image']['name'] : '';

        // Verwerk de afbeelding indien geüpload
        if (!empty($post_image)) {
            $post_image_data = file_get_contents($_FILES['upload_image']['tmp_name']);
        }

        try {
            $sql = "UPDATE posts SET post_text = :post_text, post_image = :post_image WHERE post_id = :post_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':post_text', $post_text);
            if (!empty($post_image_data)) {
                $stmt->bindParam(':post_image', $post_image_data, PDO::PARAM_LOB);
            } else {
                $stmt->bindValue(':post_image', null, PDO::PARAM_NULL);
            }
            $stmt->bindParam(':post_id', $post_id);
            $stmt->execute();

            header("Location: tweet_index.php");
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    $sql = "SELECT * FROM posts WHERE post_id = :post_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Post</title>
        </head>

        <body>
            <h2>Edit Post</h2>
            <form enctype="multipart/form-data" method="post">
                <textarea style="border: 2px solid;" name="post_text" cols="40" rows="4"><?php echo $post['post_text']; ?></textarea><br>
                <label for="upload_image">Select image:</label>
                <input type="file" name="upload_image" id="upload_image" accept="image/*"><br><br>
                <?php if (!empty($post['post_image'])) : ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($post['post_image']); ?>" alt="Posted Image"><br>
                <?php endif; ?>
                <input style="color: white; background-color:black; padding:10px; border-radius:5px;" type="submit" value="Update">
            </form>
        </body>

        </html>
<?php
    } else {
        echo "Post not found.";
    }
} else {
    echo "Post ID not provided.";
}
?>