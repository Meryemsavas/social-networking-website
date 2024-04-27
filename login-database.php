<?php
session_start();
// var_dump($_POST);

require 'conn.php';
$select_user = $conn->prepare("SELECT * FROM registration WHERE username = :gebruikersnaam");
$select_user->bindParam(":gebruikersnaam", $_POST['username']);
$select_user->execute();
$user = $select_user->fetch();


if (password_verify($_POST['password'], $user["password"])) {
    $_SESSION['gebruikersnaam'] = $_POST['username'];
    $_SESSION['user_id'] = $user['user_id'];

    header("Location: about-us.php");
} else {
    echo "niet ingelogd";
}

die();
