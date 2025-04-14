<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = mysqli_connect('localhost', 'Gyan', 'Gyan123@', 'mystore');

// Check connection
if(!$conn){
   die("Connection failed: " . mysqli_connect_error());
}

// Set charset to ensure proper encoding
mysqli_set_charset($conn, "utf8");
?>



