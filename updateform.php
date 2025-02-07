<?php
include 'server.php';
if (!isset($_POST['id'])) {
    die("pls provide movie id");
}

$id = $_POST['id'];
$sql = "SELECT * FROM movie WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    die("Movie not found.");
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Movie</title>
    <link rel="stylesheet" href="style.css">
</head>
<link rel="stylesheet" href="style2.css">
<body>
    <div class="container">
        <h2>Update Movie</h2>
        <form action="updatetodb.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            
            <label for="movieTitle">Movie Title:</label>
            <input type="text" id="movieTitle" name="movieTitle" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
            
            <label for="movieImage">Movie Image:</label>
            <input type="file" id="movieImage" name="movieImage" accept="image/*">
            <input type="hidden" name="currentImage" value="<?php echo htmlspecialchars($movie['image_path']); ?>">
            <p>Current Image Path: <?php echo htmlspecialchars($movie['image_path']); ?></p>

            <label for="showTime">Show Time:</label>
            <input type="datetime-local" id="showTime" name="showTime" value="<?php echo date('Y-m-d\TH:i', strtotime($movie['show_time'])); ?>" required>
            
            <label>Theater:</label>
            <div class="radio-group">
                <?php $theaters = ['Choa Chu Kang', 'Bukit Gombak', 'Bukit Batok', 'Jurong East']; ?>
                <?php foreach ($theaters as $theater): ?>
                    <label>
                        <input type="radio" name="theater" value="<?php echo $theater; ?>" <?php if ($movie['theater'] === $theater) echo 'checked'; ?>>
                        <?php echo $theater; ?>
                        <br><br>
                    </label>
                    <br>
                <?php endforeach; ?>
            </div>
            
            <input type="submit" value="Update Movie">
        </form>
    </div>
</body>
</html>
