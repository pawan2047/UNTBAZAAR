<?php 
$servername = "localhost";
$username = "id20048613_untbazaar";
$password = "Usavisa2023.";
$database="id20048613_untbazaar";




// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}


?>