<?php
include 'server.php';

$movieTitle = $_GET['movie'];
$cinema = isset($_GET['cinema']) ? $_GET['cinema'] : '';

$sql = "SELECT theater, show_time FROM movie WHERE title = '" . $movieTitle . "'";
if (!empty($cinema)) {
    $sql .= " AND theater = '" . $cinema . "'";
}

$result = $conn->query($sql);

$showings = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $showings[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($showings);
$conn->close();
?>
