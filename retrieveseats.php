<?php
include 'server.php';

$movie_title = $_GET['movie'];
$theater = $_GET['cinema'];
$show_time = $_GET['time'];

// First get movie ID
$movie_sql = "SELECT id FROM movie 
             WHERE title = '$movie_title' 
             AND theater = '$theater' 
             AND show_time = '$show_time'";

$result = $conn->query($movie_sql);

if ($result->num_rows > 0) {
    $movie = $result->fetch_assoc();
    $movie_id = $movie['id'];
    
    // Now get booked seats for this specific showing
    $seat_sql = "SELECT seat_number FROM bookings 
                WHERE movie_id = '$movie_id' 
                AND theater = '$theater' 
                AND show_time = '$show_time'";

    $seats_result = $conn->query($seat_sql);
    
    $booked_seats = [];
    while ($row = $seats_result->fetch_assoc()) {
        $booked_seats[] = $row['seat_number'];
    }
    
    echo json_encode($booked_seats);
} else {
    echo json_encode([]);
}

$conn->close();
?>