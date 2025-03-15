<?php
include 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $property_title = $_POST['property_title'];
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $note = $_POST['note'];

    $sql = "INSERT INTO inquiries (property_title,uname, email, note) 
            VALUES ('$property_title', '$name', '$email', '$note')";

    if ($conn->query($sql) === TRUE) {
        echo "Query submitted successfully!";
        header("Location: index.php"); // Redirect back to main page
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
