<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <?php
    include 'server.php';
    $sql = "SELECT * FROM movie";
    $result = $conn->query($sql);
?>
  <style>
      body {
          margin: 0;
          padding: 0;
          display: flex;
          flex-direction: column;
          align-items: center;
          height: 100vh;
          background: linear-gradient(135deg, #000000, #0f2027, #2c5364);
          font-family: 'Arial', sans-serif;
          color: #ffffff;
      }

      header {
          width: 100%;
          background: rgba(0, 0, 0, 0.6);
          padding: 20px;
          text-align: center;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
          position: relative;
      }

      header h1 {
          margin: 0;
          font-size: 32px;
          color: #00bfff;
          letter-spacing: 1px;
      }

      .back-button {
        padding: 12px;
        position: absolute;
        left: 20px;
        top: 50%;
        padding: auto;
        margin-top: 20px;
        margin-left: 10px;
        transform: translateY(-50%);
        background: none;
        border: none;
        border-radius: 8px;
        background: #00bfff;
        color: #000000;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s ease;
        margin-bottom: 20px;
      }

      .back-button:hover {
          background-color: #009acd;
      }

      .dashboard {
          margin-top: 20px;
          width: 100%;
          max-width: 2000px;
          padding: 20px;
          background: rgba(0, 0, 0, 0.4);
          border-radius: 15px;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      }

      .dashboard button {
          
          padding: 12px;
          border: none;
          border-radius: 8px;
          background: #00bfff;
          color: #000000;
          font-size: 18px;
          font-weight: bold;
          cursor: pointer;
          transition: 0.3s ease;
          margin-bottom: 20px;
          margin-left:10px;
      }

      .dashboard button:hover {
          background: #009acd;
      }

      .dashboard h2 {
          color: #00bfff;
          margin-bottom: 20px;
          font-size: 24px;
      }

      #moviesList {
          display: flex;
          flex-wrap: wrap;
          gap: 10px;
      }

      .movie {
          background: rgba(255, 255, 255, 0.1);
          padding: 15px;
          border-radius: 10px;
          width: 250px;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
          text-align: center;
      }

      .movie h3 {
          font-size: 18px;
          margin-bottom: 10px;
          color: #00bfff;
      }

      .movie img {
          width: 100px;
          height: 150px;
          object-fit: cover;
          margin-bottom: 10px;
          border-radius: 5px;
      }

      .movie p {
          font-size: 14px;
          color: rgba(255, 255, 255, 0.8);
          margin-bottom: 10px;
      }

      .movie .deletebutton {
          padding: 8px 12px;
          border: none;
          border-radius: 5px;
          background: #ff4d4d;
          color: #ffffff;
          font-size: 14px;
          cursor: pointer;
          transition: 0.3s ease;
      }
      .movie .updatebutton {
          padding: 8px 12px;
          border: none;
          border-radius: 5px;
          background:rgb(77, 255, 92);
          color: #ffffff;
          font-size: 14px;
          cursor: pointer;
          transition: 0.3s ease;
      }
  </style>
</head>
<body>
  <!-- Admin Dashboard -->
  <header>
      <button class="back-button" onclick="window.location.href='mainpage.php'">Back</button>
      <h1>Admin Dashboard</h1>
  </header>
  <div class="dashboard">
      <button id="addMovieBtn" onclick="window.location.href='addingform.html'">Add Movie</button>
      <h2>Current Movies</h2>
      <div id="moviesList">
      <?php if ($result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                  <div class="movie">
                      <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                      <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                      <p>Show Time: <?php echo htmlspecialchars($row['show_time']); ?></p>
                      <p>Theater: <?php echo htmlspecialchars($row['theater']); ?></p>
                      <form method="POST" action="deletefromdb.php" onsubmit="return confirm('Confirm Delete?');">
                          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                          <button type="submit" class = "deletebutton" >Delete</button>
                      </form>
                      <form method="POST" action="updateform.php">
                          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                          <button type="submit" class = "updatebutton">Update</button>
                      </form>
                      
                  </div>
              <?php endwhile; ?>
          <?php else: ?>
              <p>No movies found.</p>
          <?php endif; ?>
          <?php $conn->close(); ?>
      </div>
          </div>
</body>
</html>
