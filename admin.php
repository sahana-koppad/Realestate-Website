<?php
include 'config/db_connect.php';

// 🔹 Handle property deletion (Deletes property & associated images)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Get images associated with the property and delete from folder
    $image_result = $conn->query("SELECT image_url FROM property_images WHERE property_id='$delete_id'");
    while ($img = $image_result->fetch_assoc()) {
        unlink($img['image_url']); // Delete image file from folder
    }

    // Delete property and images from database
    $conn->query("DELETE FROM properties WHERE id='$delete_id'");
    $conn->query("DELETE FROM property_images WHERE property_id='$delete_id'");
    
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all fields are set to avoid "Undefined array key" errors
    $title = isset($_POST['title']) ? $_POST['title'] : "";
    $address = isset($_POST['address']) ? $_POST['address'] : "";
    $pincode = isset($_POST['pincode']) ? $_POST['pincode'] : "";
    $price = isset($_POST['price']) ? $_POST['price'] : "";
    $description = isset($_POST['description']) ? $_POST['description'] : "";

    // Validate if required fields are filled
    if (empty($title) || empty($address) || empty($pincode) || empty($price) || empty($description)) {
        die("Error: All fields are required!");
    }

    // Prepare SQL query to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO properties (title, address, pincode, price, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $address, $pincode, $price, $description);

    if ($stmt->execute()) {
        echo "✅ Property added successfully!";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}

        // 🔹 Handle Image Upload (Store paths, but do NOT display images in the panel)
        if (!empty($_FILES['property_images']['name'][0])) {
            $upload_dir = "uploads/";
            foreach ($_FILES['property_images']['tmp_name'] as $key => $tmp_name) {
                $filename = basename($_FILES['property_images']['name'][$key]);
                $target_file = $upload_dir . time() . "_" . $filename;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $conn->query("INSERT INTO property_images (image_url) VALUES ('$target_file')");
                }
            }
        }
   else {
        echo "Error: " . $conn->error;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
         <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .delete-btn {
            color: white;
            background-color: red;
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
        form {
            margin-top: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
        }
        .submit-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2>Admin Panel</h2>

<!-- 🔹 Table: User Inquiries -->
<h3>User Messages</h3>
<table>
    <tr>
        <th>Property ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Submitted At</th>
    </tr>
    <?php
    $query_result = $conn->query("SELECT * FROM inquiries");
    while ($row = $query_result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['property_title']}</td>
                <td>{$row['uname']}</td>
                <td>{$row['email']}</td>
                <td>{$row['note']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    ?>
</table>

<!-- 🔹 Table: Properties List (No Images Displayed) -->
<h3>Properties</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Location</th>
        <th>Pincode</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    <?php
    $prop_result = $conn->query("SELECT * FROM properties");
    while ($row = $prop_result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['address']}</td>
                <td>{$row['pincode']}</td>
                <td>{$row['description']}</td>
                <td>
                    <a href='admin.php?delete_id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>
              </tr>";
    }
    ?>
</table>

<!-- 🔹 Form: Add New Property -->
<div class="box-container">
<h3>Add New Property</h3>
<form method="post" action="admin.php" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required><br>
    <input type="text" name="address" placeholder="address" required><br>
    <input type="text" name="pincode" placeholder="Pincode" required><br>
    <input type="text" name="area" placeholder="area" required><br>
    <input type="text" name="price" placeholder="price" required><br>
    <textarea name="description" placeholder="Description" required></textarea><br>
    <input type="file" name="property_images[]" multiple accept="image/*"><br>
    <input type="submit" name="add_property" value="Add Property">
</form>
</div>
<div style="text-align:center;">
<form method="POST" action="logout.php">
        <button type="submit">Sign Out</button>
    </form>
</body>
</html>
