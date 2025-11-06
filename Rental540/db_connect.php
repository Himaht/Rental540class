<?php
$servername = "localhost";
$username = "root";   // default WAMP username
$password = "";       // default WAMP password is empty
$dbname = "rental540";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
