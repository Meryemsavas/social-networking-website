<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chirpify";

try {
  $conn = new PDO("mysql:host=$servername;dbname=chirpify", $username, $password);
  // Set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  die(); // Stop the script from executing further
}

//session_start();


?> 
