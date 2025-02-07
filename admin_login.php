<?php

session_start();


$admin_username = "admin";
$admin_password = "password123"; 


if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php"); 
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}

/* this is the code that checks if the user is already logged in and redirects them to the dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php"); 
    exit();
}
    */
?>
<!-- <?php
session_start();




if (isset($_POST['login'])) {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];


    $url = $astral_db_url . "?username=" . urlencode($input_username);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    
    curl_close($ch);

    $user = json_decode($response, true);
    

    if ($user && password_verify($input_password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php"); 
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
/* this is the code that checks if the user is already logged in and redirects them to the dashboard

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php"); 
    exit();
}
    */
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php
        if (isset($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>

            <input type="submit" name="login" value = "Log In"></input>
        </form>
    </div>
</body>
</html>