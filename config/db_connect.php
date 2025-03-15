<?php
$host = "localhost";  // Change if using a remote server
$user = "root";       // Your database username
$pass = "";           // Your database password
$dbname = "r";  // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else {
    echo "connection successfull";
}
?>
