<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>About us</title>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Chirpify</h2>
            <?php

            if (isset($_SESSION['gebruikersnaam'])) {
                // Gebruikersnaam is ingesteld, toon deze in de navigatiebalk
                echo "<p style='color:white;'>&nbsp;&nbsp;&nbsp;WELCOME, {$_SESSION['gebruikersnaam']}</p>";
            } else {
                // Gebruikersnaam is niet ingesteld, misschien is de gebruiker niet ingelogd
                echo "<p>&nbsp;WELCOME, Guest</p>"; // Of toon een standaardbericht voor niet-ingelogde gebruikers
            }

            ?>
            <ul>
                <li><a href="all_posts.php"><i class="fa fa-house"></i> Home</a></li>
                <li><a href="profile.php"><i class="fa-solid fa-user"></i></i> Profile</a></li>
                <li><a href="about-us.php"><i class="fa-solid fa-circle-info"></i></i></i> About</a></li>
                <li><a href="index.php"><i class="fa-solid fa-right-from-bracket"></i> Log out</a></li>
                <!-- <li><a href="register.php">Register</a></li> -->
                <?php if (!isset($_SESSION['gebruikersnaam'])) { ?><li><a href="index.php"> Login</a></li> <?php } ?>
            </ul>
            <div class="social_media">
                <a href="https://www.linkedin.com/in/jaivyvdwillik/"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="https://www.linkedin.com/in/meryem-savas-364389290/"><i class="fa-brands fa-linkedin-in"></i></i></a>
                <a href="https://www.linkedin.com/in/georgino-peterson-a48781196/"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
        </div>
        <div class="main_content">