<?php
include 'server.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get movie details
    $movie_title = $_POST['movie'];
    $theater = $_POST['cinema'];
    $show_time = $_POST['time'];
    $seats = json_decode($_POST['seats']);

    // First get movie ID
    $movie_sql = "SELECT id FROM movie 
                 WHERE title = '$movie_title' 
                 AND theater = '$theater' 
                 AND show_time = '$show_time'";

    $result = $conn->query($movie_sql);

    if ($result->num_rows === 0) {
        die("Error: Movie showing not found");
    }

    $movie = $result->fetch_assoc();
    $movie_id = $movie['id'];

    // Insert bookings
    foreach($seats as $seat) {
        $insert_sql = "INSERT INTO bookings (movie_id, theater, show_time, seat_number) 
                      VALUES ('$movie_id', '$theater', '$show_time', '$seat')";

        if (!$conn->query($insert_sql)) {
            die("Error booking seat $seat: " . $conn->error);
        }
    }

    echo "Booking successful!";
    $conn->close();
}
?>
