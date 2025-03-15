<?php
session_start(); 


if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin.php"); 
    exit;
}


$username = '';
$password = '';
$error = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

    
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['loggedin'] = true;
            header("Location: admin.php"); 
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin login</title>
    <link rel="stylesheet"
     href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/pooper.min.js"></script>
     <script src="https://maxcdn.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #d6e4f3;
        }
        .box-container {
            border: 3px solid rgb(87, 10, 70);
            padding: 20px;
            border-radius: 10px;
            background-color: rgb(246, 250, 250);
            width: 400px;
        }
    </style>
</head>
<body>
        <div class="box-container">
            <h3>Admin Login</h3>
                <form id="loginPage" method="post">
                    <div class="form-group">
                        <label for="username">Username Name</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="User Name" required>
                    </div>
                    <div class="form-group">
                        <label for="password"></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                    </div>
                    <div class="text-center">
                        <button style="color:#f36409" type="submit">Login</button>
                    </div>  
                </form>
                <p><?php echo $error; ?></p>
             </div>
        </div>
</body>
</html>