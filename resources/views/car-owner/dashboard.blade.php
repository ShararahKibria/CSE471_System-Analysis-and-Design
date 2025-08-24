<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Dashboard</title>
  <style>
    body, html {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      background-color: black;
      font-family: Arial, sans-serif;
    }

    /* Top bar */
    .topbar {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      padding: 1rem;
      background-color: black;
      z-index: 30;
    }

    .topbar button {
      background: none;
      border: none;
      cursor: pointer;
      color: white;
      font-size: 2rem;
    }

    /* Container */
    .container {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    /* Welcome text animation */
    .welcome-text {
      position: absolute;
      color: white;
      font-size: 3rem;
      font-weight: 800;
      opacity: 0;
      transform: translateX(200px);
      animation: fadeInSlide 1s forwards;
    }

    @keyframes fadeInSlide {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: -300px; /* hidden by default */
      width: 300px;
      height: 100%;
      background: rgba(0,0,0,0.8);
      backdrop-filter: blur(10px);
      color: white;
      padding: 2rem;
      box-sizing: border-box;
      transition: left 0.3s ease;
      z-index: 40;
    }

    .sidebar.open {
      left: 0;
    }

    /* Overlay */
    .overlay {
      position: fixed;
      inset: 0;
      background-color: rgba(0,0,0,0.4);
      backdrop-filter: blur(4px);
      z-index: 20;
    }
  </style>
</head>
<body>

  <!-- Top bar -->
  <div class="topbar">
    <button id="sidebarBtn">&#9776;</button>
  </div>

  <!-- Container with 3D iframe and welcome text -->
  <div class="container">
    <iframe src="https://my.spline.design/particlenebula-rWy58n5JfPxlST2LHaWZGVN4/"></iframe>
    <div class="welcome-text" id="welcomeText">Welcome to dashboard</div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2>Teacher Menu</h2>
    <p>Welcome!</p>
    <!-- Add sidebar links here -->
    <button onclick="closeSidebar()">Close</button>
  </div>

  <!-- Overlay -->
  <div class="overlay" id="overlay" style="display:none;" onclick="closeSidebar()"></div>

  <script>
    // Get teacher info from localStorage
    const teacherName = localStorage.getItem('userName') || '';
    const teacherEmail = localStorage.getItem('userEmail') || '';
    const teacherId = localStorage.getItem('userId') || '';

    // Set welcome text
    document.getElementById('welcomeText').textContent = `Welcome to dashboard ${teacherName}`;

    // Sidebar toggle
    const sidebarBtn = document.getElementById('sidebarBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebarBtn.addEventListener('click', () => {
      sidebar.classList.toggle('open');
      overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
    });

    function closeSidebar() {
      sidebar.classList.remove('open');
      overlay.style.display = 'none';
    }
  </script>

</body>
</html>
