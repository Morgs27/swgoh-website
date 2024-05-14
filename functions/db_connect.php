<?php
// Four variables to connect to the database
$servername = "192.168.0.8:3306";
$username = "root";
$password = "swgohpassword";
$dbname = "swgohger_website";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>