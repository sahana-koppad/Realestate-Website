<?php
include 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $property_id = $_POST['property_id'];
    $target_dir = "uploads/";
    $image_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_url = "uploads/" . $image_name;
        $sql = "INSERT INTO property_images (property_id, image_url) VALUES ('$property_id', '$image_url')";
        if ($conn->query($sql) === TRUE) {
            echo "Image uploaded successfully!";
        } else {
            echo "Database error: " . $conn->error;
        }
    } else {
        echo "Error uploading image.";
    }
}
?>
