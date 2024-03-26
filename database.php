<?php
require_once "config.php"; // Including the configuration php file to access the database credentials.

// Create the database connection with the parameters being the credentials from config.php.
$conn = new mysqli($serverName, $username, $password, $dbName); 

// Check if the connection was successful.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
