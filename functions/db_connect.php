<?php
// Four variables to connect to the database
$servername = "mysql";
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