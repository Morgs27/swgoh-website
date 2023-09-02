<?php
// Four variables to connect to the database
$servername = "localhost:3306";
$username = "swgohger_Morgs27";
$password = "Morg=2708";
$dbname = "swgohger_website";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>