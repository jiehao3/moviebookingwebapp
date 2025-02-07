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
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #000000, #0f2027, #2c5364);
            font-family: 'Arial', sans-serif;
            color: #ffffff;
            overflow: hidden;
        }

        .container {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #00bfff;
            font-size: 28px;
            letter-spacing: 1px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            color: #00bfff;
        }

        input[type="text"], input[type="password"] {
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 16px;
        }

        input[type="text"]::placeholder, input[type="password"]::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        button {
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #00bfff;
            color: #000000;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: #009acd;
        }

        .error-message {
            color: #ff4d4d;
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        
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

            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>