<?php
include 'server.php';

// Check if the ID is set
if (!isset($_POST['id'])) {
    die("Invalid request. Movie ID is missing.");
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
<link rel="stylesheet" href="style.css">
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
        .glass-container {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
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
        input[type="text"], input[type="file"], input[type="datetime-local"], select {
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 16px;
        }
        input[type="text"]::placeholder,
        input[type="datetime-local"]::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: rgba(255, 255, 255, 0.1);
            cursor: pointer;
        }
        input[type="submit"] {
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
        input[type="submit"]:hover {
            background: #009acd;
        }
    </style>
<body>
    <div class="glass-container">
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
