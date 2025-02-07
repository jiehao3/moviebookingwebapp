<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Ticket Booking</title>
  <?php
      include 'server.php';
      $sql = "SELECT DISTINCT title, image_path FROM movie";
      $result = $conn->query($sql);
  ?>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="maintitle">
        <h1>♦ Diamond Village ♦</h1>
    </header>
    
  <div class="top-nav">
    <div class="nav-links">
      <a href="#" style="color: white;">Home</a>
      <a href="#"style="color: white;">Contact</a>
      <a href="admin_login.php"style="color: white;">Admin Login</a>
      <span id = "darkmode">Dark Mode</span>
        <label class="switch">
          <input type="checkbox" id="theme-switch">
          <span class="slider"></span>
        </label>  
      </div>
    </div>
  </div>

  <div class = "spacer"></div>

  <div class="search-bar">
      <input type="text" placeholder = "Search Movies...">
    </div>
  <div class="movie-tabs">
    <button class="active" onclick="showTab('now-showing')">Now Showing</button>
    <button onclick="showTab('coming-soon')">Coming Soon</button>
  </div>

  <div class="movie-selection" id="now-showing-container">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="movie-card">
          <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
          <button  style= 'background-color: #00aaff; font-size: 13px; font-family: Poppins; font-weight: bold;'
             onclick="openModal('<?php echo addslashes(htmlspecialchars($row['title'])); ?>')">
            <?php echo htmlspecialchars($row['title']); ?>
          </button>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No movies available.</p>
    <?php endif; ?>
  </div>

  <div class="movie-selection" id="coming-soon-container" style="display: none;">
    <div class="movie-card">
      <img src="images/captainamerica.png" alt="Captain America: Brave New World">
      <button style= 'background-color:red; font-size: 11px; font-family: Poppins; font-weight: bold;'>Captain America: Brave New World</button>
    </div>
    <?php
    for ($i = 0; $i < 3; $i++) {
      echo "<div class='movie-card'>
              <img src='images/cmgsoon.jpg' alt='Coming Soon'>
              <button style='background-color:red; font-size: 11px; font-family: Poppins; font-weight: bold;'>Coming Soon</button>
            </div>";
    }
  ?>
  </div>

  <div class="modal-overlay" id="modal-overlay">
    <div class="modal-content">
      <span class="close-modal" onclick="closeModal()">&times;</span>
      <h2 id="selected-movie-title"></h2>

      <!-- Cinema (Theater) Selection -->
      <div class="section" id="cinemaSection">
        <h3>Select Cinema</h3>
        <!-- Dynamically populated cinemas -->
        <div class="options" id="cinemaOptions"></div>
      </div>

      <!-- Timing Selection -->
      <div class="section" id="timeSection" style="display: none;">
        <h3>Select Time</h3>
        <div class="options" id="timeOptions"></div>
      </div>

      <!-- Seat Selection -->
      <div class="section" id="seatSection" style="display: none;">
        <div class="screen">SCREEN</div>
        <div class="seats" id="seatMap"></div>
        <button class="submit-btn" id="submitSeatsBtn" style="display: none;" onclick="showSummary()">Submit Seats</button>
      </div>

      <!-- Booking Summary -->
      <div class="section" id="summarySection" style="display: none;">
        <h3>Booking Summary</h3>
          <div id="summaryDetails"></div>
        <button class="submit-btn" onclick="submitBooking()">Confirm Booking</button>
      </div>
    </div>
  </div>

  <script>
    let bookingState = {
      movie: '',
      cinema: '',
      time: '',
      seats: []
    };

    
    function openModal(movieTitle) {
      bookingState.movie = movieTitle;
      document.getElementById('selected-movie-title').textContent = movieTitle;
      document.getElementById('modal-overlay').style.display = 'flex';
      fetch(`get_showings.php?movie=${encodeURIComponent(movieTitle)}`)
        .then(response => response.json())
        .then(data => {
          populateCinemas(data);
        })
        .catch(error => console.error("Error fetching showings:", error));
      
      showSection('cinemaSection');
    }
    
    function closeModal() {
      document.getElementById('modal-overlay').style.display = 'none';
      resetBooking();
    }
    function populateCinemas(showings) {
      const cinemas = [...new Set(showings.map(showing => showing.theater))];
      const cinemaOptions = cinemas.map(cinema => 
          `<button class="option-btn" onclick="selectCinema('${cinema}')">${cinema}</button>`
      ).join('');
      
      document.getElementById('cinemaOptions').innerHTML = cinemaOptions;
    }

    function showSection(sectionId) {
      ['cinemaSection', 'timeSection', 'seatSection', 'summarySection'].forEach(id => {
        document.getElementById(id).style.display = id === sectionId ? 'block' : 'none';
      });
    }

    function selectCinema(cinema) {
      bookingState.cinema = cinema;
      fetch(`get_showings.php?movie=${encodeURIComponent(bookingState.movie)}&cinema=${encodeURIComponent(cinema)}`)
        .then(response => response.json())
        .then(data => {
          populateTimes(data);
        })
        .catch(error => console.error("Error fetching times:", error));
      showSection('timeSection');
    }

    function populateTimes(showings) {
      var timenow = new Date();
      var html = "";
      showings.forEach(function(showing) {
        var showDate = new Date(showing.show_time);
        var formattedTime = showDate.toLocaleString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true});
        if (showDate < timenow) {
          html += `<button class="optiondisabled-btn" onclick="selectTime('${formattedTime}')">${formattedTime}</button>`;
        }
        else {
          html += `<button class="option-btn" onclick="selectTime('${formattedTime}')">${formattedTime}</button>`;
        }
      });

      document.getElementById('timeOptions').innerHTML = html;
    }

    function selectTime(time) {
      bookingState.time = time;
      showSection('seatSection');
      generateSeats();
    }

    function generateSeats() {
      const rows = ['A', 'B', 'C', 'D'];
      let seatsHTML = '';

      rows.forEach(row => {
        for (let i = 1; i <= 8; i++) {
          const seat = `${row}${i}`;
          const isOccupied = Math.random() < 0.3;
          seatsHTML += `
            <button class="seat ${isOccupied ? 'occupied' : ''}" 
              ${isOccupied ? 'disabled' : ''}
              onclick="toggleSeat('${seat}')">
              ${seat}
            </button>`;
        }
      });

      document.getElementById('seatMap').innerHTML = seatsHTML;
    }

    function toggleSeat(seat) {
      const index = bookingState.seats.indexOf(seat);
      if (index === -1) {
        bookingState.seats.push(seat);
      } else {
        bookingState.seats.splice(index, 1);
      }
      updateSeatDisplay();
      const submitBtn = document.getElementById('submitSeatsBtn');
      submitBtn.style.display = bookingState.seats.length > 0 ? 'block' : 'none';
      
    }

    function updateSeatDisplay() {
      document.querySelectorAll('.seat').forEach(seatBtn => {
        const seatId = seatBtn.textContent.trim();
        seatBtn.classList.toggle('selected', bookingState.seats.includes(seatId));
      });
    }

    function showSummary() {
      showSection('summarySection');
      document.getElementById('summaryDetails').innerHTML = `
        <p><strong>Movie:</strong> ${bookingState.movie}</p>
        <p><strong>Cinema:</strong> ${bookingState.cinema}</p>
        <p><strong>Time:</strong> ${bookingState.time}</p>
        <p><strong>Seats:</strong> ${bookingState.seats.join(', ')}</p>
        <p><strong>Total:</strong> $${bookingState.seats.length * 15}</p>
      `;
    }

    function submitBooking() {
      alert(`Booking Confirmed!\n${document.getElementById('summaryDetails').innerText}`);
      closeModal();
    }

    function resetBooking() {
      bookingState = { movie: '', cinema: '', time: '', seats: [] };
      document.getElementById('seatMap').innerHTML = '';
      document.getElementById('summaryDetails').innerHTML = '';
    }

    const themeSwitch = document.getElementById('theme-switch');
    const topNav = document.querySelector('.top-nav');
    const darkmode = document.getElementById('darkmode')
    const homeLink = document.querySelector('a[href="#"]:nth-child(1)');
    const contactLink = document.querySelector('a[href="#"]:nth-child(2)');
    const adminLink = document.querySelector('a[href="admin_login.php"]');
    themeSwitch.addEventListener('change', () => {
    document.body.classList.toggle('light-mode');
    if (themeSwitch.checked) {
    topNav.style.background = "linear-gradient(to bottom, #ffffff, #f2f2f2)";
    topNav.style.color = "black";
    homeLink.style.color = "black";
    contactLink.style.color = "black";
    adminLink.style.color = "black";
    darkmode.style.color = "black";
  } else {

    topNav.style.background = "linear-gradient(to bottom, #333, #000)";
    topNav.style.color = "white";
    homeLink.style.color = "white";
    contactLink.style.color = "white";
    adminLink.style.color = "white";
    darkmode.style.color = "white";
    }});
  
    function showTab(tabName) {
      const nowShowing = document.getElementById('now-showing-container');
      const comingSoon = document.getElementById('coming-soon-container');
      const buttons = document.querySelectorAll('.movie-tabs button');
      
      buttons.forEach(button => button.classList.remove('active'));
      event.target.classList.add('active');

      if (tabName === 'now-showing') {
        nowShowing.style.display = 'grid';
        comingSoon.style.display = 'none';
      } else if (tabName === 'coming-soon') {
        nowShowing.style.display = 'none';
        comingSoon.style.display = 'grid';
      }
    }

    const searchInput = document.querySelector('.search-bar input');
    searchInput.addEventListener('input', () => {
      const searchTerm = searchInput.value.toLowerCase();
      const movieCards = document.querySelectorAll('.movie-card');
      movieCards.forEach(card => {
        const title = card.querySelector('img').alt.toLowerCase();
        if (title.includes(searchTerm)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>