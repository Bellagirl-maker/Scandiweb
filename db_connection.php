<?php
$servername = "localhost";
$username = "id22409566_root";
$password = "AdMiN@24";
$dbname = "id22409566_product_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
