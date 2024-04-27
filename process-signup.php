<?php
session_start();

require 'conn.php';

$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Hash het wachtwoord
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $conn->prepare("INSERT INTO registration(firstName, lastName, username, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $firstName);
    $stmt->bindParam(2, $lastName);
    $stmt->bindParam(3, $username);
    $stmt->bindParam(4, $email);
    $stmt->bindParam(5, $hashedPassword);
    $stmt->execute();

    $user_id = $conn->lastInsertId();

    header("Location: index.php");
    die();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
