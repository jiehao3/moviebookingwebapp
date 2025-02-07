<?php
include 'server.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["movieImage"]["name"]);
    move_uploaded_file($_FILES["movieImage"]["tmp_name"], $target_file);

    $title = $_POST['movieTitle'];
    $show_time = $_POST['showTime'];
    $theater = $_POST['theater'];

    $sql = "INSERT INTO movie (title, image_path, show_time, theater) 
    VALUES ('$title', '$target_file', '$show_time', '$theater')";
    
    
    if ($conn -> query($sql) === TRUE) {
        header("Location: admin_dashboard.php");
        echo "Movie added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>
