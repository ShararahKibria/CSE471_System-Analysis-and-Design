<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Owner Dashboard</title>
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
      z-index: 10;
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
      background: rgba(0,0,0,0.9);
      backdrop-filter: blur(10px);
      color: white;
      padding: 2rem;
      box-sizing: border-box;
      transition: left 0.3s ease;
      z-index: 40;
      border-right: 2px solid rgba(255,255,255,0.1);
    }

    .sidebar.open {
      left: 0;
    }

    .sidebar h2 {
      margin-bottom: 1rem;
      color: #fff;
      font-size: 1.5rem;
    }

    .sidebar p {
      margin-bottom: 2rem;
      color: #ccc;
    }

    .sidebar button {
      background: linear-gradient(45deg, #7e22ce, #a855f7);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      margin-bottom: 1rem;
      width: 100%;
      transition: transform 0.2s ease;
    }

    .sidebar button:hover {
      transform: translateY(-2px);
    }

    /* Overlay */
    .overlay {
      position: fixed;
      inset: 0;
      background-color: rgba(0,0,0,0.4);
      backdrop-filter: blur(4px);
      z-index: 20;
      display: none;
    }

    /* ===== Modal Styles ===== */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.8); /* Darker background */
      backdrop-filter: blur(5px);
      display: none; /* hidden by default */
      justify-content: center;
      align-items: center;
      z-index: 1000;
      padding: 20px;
    }

    .modal-content {
      background: linear-gradient(145deg, #1f1f1f, #2d2d2d);
      padding: 30px;
      border-radius: 20px;
      width: 450px;
      max-width: 90%;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
      position: relative;
      border: 1px solid rgba(255,255,255,0.1);
    }

    .modal-close {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 28px;
      background: none;
      border: none;
      cursor: pointer;
      color: #fff;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      transition: background 0.3s ease;
    }

    .modal-close:hover {
      background: rgba(255,255,255,0.1);
    }

    .modal-title {
      color: white;
      font-size: 2rem;
      margin-bottom: 10px;
      background: linear-gradient(45deg, #7e22ce, #a855f7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .modal-subtitle {
      color: #ccc;
      margin-bottom: 30px;
      font-size: 1.1rem;
    }

    .category-options {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 20px;
    }

    .category-option {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 15px;
      text-decoration: none;
      color: white;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .category-option::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(126, 34, 206, 0.1), transparent);
      transition: left 0.5s ease;
    }

    .category-option:hover::before {
      left: 100%;
    }

    .category-option:hover {
      background: linear-gradient(145deg, #3a3a3a, #2a2a2a);
      border-color: rgba(126, 34, 206, 0.3);
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(126, 34, 206, 0.2);
    }

    .category-info {
      text-align: left;
      z-index: 1;
    }

    .category-name {
      font-weight: bold;
      font-size: 1.1rem;
      margin-bottom: 5px;
      color: white;
    }

    .category-desc {
      font-size: 0.9rem;
      color: #aaa;
      line-height: 1.4;
    }

    .category-icon {
      font-size: 2rem;
      z-index: 1;
    }

    /* Debug styles */
    .debug-info {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: rgba(0,0,0,0.8);
      color: white;
      padding: 10px;
      border-radius: 5px;
      font-size: 12px;
      z-index: 1001;
    }

    /* Loading indicator */
    .loading {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(0,0,0,0.8);
      color: white;
      padding: 20px;
      border-radius: 10px;
      z-index: 1002;
    }

    @media (max-width: 768px) {
      .welcome-text {
        font-size: 2rem;
        text-align: center;
        padding: 0 1rem;
      }

      .modal-content {
        width: 90%;
        padding: 20px;
      }

      .modal-title {
        font-size: 1.5rem;
      }
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
    <h2>Car Owner Menu</h2>
    <p>Welcome!</p>
    <!-- Button to trigger modal -->
    <button id="openModalBtn">Choose Services</button>
    <br><br>
    <button onclick="closeSidebar()">Close</button>
  </div>

  <!-- Sidebar Overlay -->
  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

  <!-- Modal Overlay -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal-content">
      <button class="modal-close" id="modalClose">&times;</button>
      <h2 class="modal-title">Choose Your Service</h2>
      <p class="modal-subtitle">Select the perfect category for your needs</p>

      <div class="category-options">
        <a href="/cars/find_driver" class="category-option" onclick="handleLinkClick(this, event)">
          <div class="category-info">
            <div class="category-name">Driver</div>
            <div class="category-desc">Find trusted drivers for your family</div>
          </div>
          <div class="category-icon">👨‍👩‍👧‍👦</div>
        </a>

        <a href="/cars/find_mechanics" class="category-option" onclick="handleLinkClick(this, event)">
          <div class="category-info">
            <div class="category-name">Find a mechanic around you</div>
            <div class="category-desc">Get connected with trusted mechanics in your area</div>
          </div>
          <div class="category-icon">🔧</div>
        </a>

        <a href="/cars/find_premium_service" class="category-option" onclick="handleLinkClick(this, event)">
          <div class="category-info">
            <div class="category-name">Find Premium Service</div>
            <div class="category-desc">Get the best premium services for your vehicle at your doorstep</div>
          </div>
          <div class="category-icon">🚗</div>
        </a>
      </div>
    </div>
  </div>

  <!-- Loading indicator -->
  <div class="loading" id="loading">Loading...</div>

  <!-- Debug info -->
  <div class="debug-info" id="debugInfo" style="display: none;">
    Click events: <span id="clickCount">0</span>
  </div>

  <script>
    let clickCount = 0;

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

    // Modal logic
    const openModalBtn = document.getElementById('openModalBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalClose = document.getElementById('modalClose');

    openModalBtn.addEventListener('click', () => {
      modalOverlay.style.display = 'flex';
      console.log('Modal opened');
    });

    modalClose.addEventListener('click', () => {
      modalOverlay.style.display = 'none';
      console.log('Modal closed by close button');
    });

    // FIXED: More specific modal overlay click handling
    modalOverlay.addEventListener('click', (e) => {
      // Only close if clicking directly on the overlay, not on child elements
      if (e.target === modalOverlay) {
        modalOverlay.style.display = 'none';
        console.log('Modal closed by overlay click');
      }
    });

    // Handle link clicks with debugging
    function handleLinkClick(linkElement, event) {
      clickCount++;
      document.getElementById('clickCount').textContent = clickCount;
      
      const href = linkElement.getAttribute('href');
      console.log('Link clicked:', href);
      
      // Show loading indicator
      document.getElementById('loading').style.display = 'block';
      
      // Close modal before navigating
      modalOverlay.style.display = 'none';
      
      // Small delay to ensure modal closes smoothly
      setTimeout(() => {
        console.log('Navigating to:', href);
        window.location.href = href;
      }, 100);
      
      // Prevent default link behavior temporarily
      event.preventDefault();
    }

    // Enable debug mode (remove this in production)
    function enableDebug() {
      document.getElementById('debugInfo').style.display = 'block';
    }

    // Test function to verify routes exist
    function testRoutes() {
      const routes = [
        '/cars/find_driver',
        '/cars/find_mechanics', 
        '/cars/find_premium_service'
      ];
      
      routes.forEach(route => {
        fetch(route, { method: 'HEAD' })
          .then(response => {
            console.log(`Route ${route}:`, response.status === 200 ? 'EXISTS' : 'NOT FOUND');
          })
          .catch(error => {
            console.log(`Route ${route}: ERROR -`, error.message);
          });
      });
    }

    // Call test function on load (remove in production)
    // testRoutes();
    // enableDebug();

    // Set welcome text with user name
    // You can pass this from your Laravel backend
    const userName = '{{ $name ?? "Car Owner" }}';
    document.getElementById('welcomeText').textContent = `Welcome to dashboard ${userName}`;
  </script>

</body>
</html>