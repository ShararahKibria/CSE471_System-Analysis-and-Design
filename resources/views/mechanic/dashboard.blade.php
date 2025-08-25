<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mechanic Dashboard</title>
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
      text-align: center;
      color: white;
      font-size: 3rem;
      font-weight: 800;
      opacity: 0;
      transform: translateY(50px);
      animation: fadeInSlide 1.5s forwards;
    }

    .welcome-text span {
      color: #00ff99; /* highlight mechanic's name */
      font-size: 3.5rem; /* slightly bigger */
    }

    @keyframes fadeInSlide {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: -300px;
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

    .profile-info {
      margin-top: 1.5rem;
      font-size: 0.9rem;
      line-height: 1.6;
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
    <iframe src='https://my.spline.design/timeliquid-cDLZvy0lk2oyaMZeGX8pi5VB/' frameborder='0' width='100%' height='100%'></iframe>
    <div class="welcome-text" id="welcomeText">
      Welcome <span>{{ $mechanicName }}</span> <br>
      to your Dashboard
    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
  <h2>Mechanic Menu</h2>
  <p>Welcome {{ $mechanicName }}</p>
  
  <!-- Cars button -->
  <form action="{{ url('/mechanic/cars') }}" method="GET">
      <button type="submit">Cars</button>
  </form>

  <button onclick="closeSidebar()">Close</button>
</div>


  <!-- Overlay -->
  <div class="overlay" id="overlay" style="display:none;" onclick="closeSidebar()"></div>

  <script>
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
