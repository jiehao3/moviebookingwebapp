<?php
include 'server.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_id = $_POST['id']; // Get the movie ID for updating

    // Check if movie ID is provided
    if (empty($movie_id)) {
        die("Error: Movie ID is required for updating.");
    }

    $target_dir = "uploads/";

    // If a new file is uploaded, process it
    if (!empty($_FILES["movieImage"]["name"])) {
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["movieImage"]["name"]);
        move_uploaded_file($_FILES["movieImage"]["tmp_name"], $target_file);
    } else {
        // If no new file is uploaded, retain the existing image path
        $target_file = $_POST['currentImage'];
    }

    $title = $_POST['movieTitle'];
    $show_time = $_POST['showTime'];
    $theater = $_POST['theater'];

    // Update query
    $sql = "UPDATE movie SET title='$title', image_path='$target_file', show_time='$show_time', theater='$theater' WHERE id='$movie_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>
