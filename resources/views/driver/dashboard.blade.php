<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
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



    /* Sidebar */
    .sidebar {
      position: fixed;
      left: -280px;
      top: 0;
      width: 280px;
      height: 100vh;
      background: linear-gradient(180deg, #1a1a1a 0%, #2d1b69 100%);
      backdrop-filter: blur(10px);
      border-right: 1px solid rgba(126, 34, 206, 0.2);
      transition: left 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      z-index: 20;
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
    }

    .sidebar.open {
      left: 0;
    }

    .sidebar-header {
      padding: 2rem 1.5rem 1rem;
      border-bottom: 1px solid rgba(126, 34, 206, 0.2);
    }

    .sidebar-title {
      color: white;
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
      background: linear-gradient(45deg, #7e22ce, #a855f7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .sidebar-buttons {
      padding: 1.5rem 0;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .sidebar-button {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem 1.5rem;
      color: #e5e7eb;
      background: transparent;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1rem;
      font-weight: 500;
      position: relative;
      overflow: hidden;
    }

    .sidebar-button::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      width: 0;
      height: 100%;
      background: linear-gradient(90deg, rgba(126, 34, 206, 0.1), rgba(168, 85, 247, 0.1));
      transition: width 0.3s ease;
    }

    .sidebar-button:hover {
      color: #a855f7;
      transform: translateX(8px);
    }

    .sidebar-button:hover::before {
      width: 100%;
    }

    .sidebar-button-icon {
      width: 20px;
      height: 20px;
      fill: currentColor;
      z-index: 1;
    }

    .sidebar-button-text {
      z-index: 1;
    }

    /* Sidebar Toggle Button */
    .sidebar-toggle {
      position: fixed;
      top: 50%;
      left: 20px;
      transform: translateY(-50%);
      width: 50px;
      height: 50px;
      background: linear-gradient(45deg, #7e22ce, #a855f7);
      border: none;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 15;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(126, 34, 206, 0.4);
    }

    .sidebar-toggle:hover {
      transform: translateY(-50%) scale(1.1);
      box-shadow: 0 6px 20px rgba(126, 34, 206, 0.6);
    }

    .sidebar-toggle.open {
      left: 300px;
    }

    .hamburger {
      width: 24px;
      height: 18px;
      position: relative;
    }

    .hamburger span {
      display: block;
      position: absolute;
      height: 2px;
      width: 100%;
      background: white;
      border-radius: 2px;
      opacity: 1;
      left: 0;
      transform: rotate(0deg);
      transition: 0.25s ease-in-out;
    }

    .hamburger span:nth-child(1) {
      top: 0px;
    }

    .hamburger span:nth-child(2) {
      top: 8px;
    }

    .hamburger span:nth-child(3) {
      top: 16px;
    }

    .hamburger.open span:nth-child(1) {
      top: 8px;
      transform: rotate(135deg);
    }

    .hamburger.open span:nth-child(2) {
      opacity: 0;
      left: -60px;
    }

    .hamburger.open span:nth-child(3) {
      top: 8px;
      transform: rotate(-135deg);
    }

    /* Overlay */
    .sidebar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.5);
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 19;
    }

    .sidebar-overlay.active {
      opacity: 1;
      visibility: visible;
    }

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

    .welcome-text {
      position: absolute;
      color: white;
      font-size: 3rem;
      font-weight: 800;
      opacity: 0;
      transform: translateX(200px);
      animation: fadeInSlide 1s forwards;
      text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
    }

    @keyframes fadeInSlide {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .welcome-text {
        font-size: 2rem;
        text-align: center;
        padding: 0 1rem;
      }
    }
  </style>
</head>
<body>



  <!-- Sidebar Toggle Button -->
  <button class="sidebar-toggle" id="sidebarToggle">
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </button>

  <!-- Sidebar Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h2 class="sidebar-title">Account</h2>
    </div>
    <div class="sidebar-buttons">
      <button class="sidebar-button" onclick="navigateTo('./update-profile')">
        <svg class="sidebar-button-icon" viewBox="0 0 24 24">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        <span class="sidebar-button-text" href="./update-profile">Update Profile</span>
      </button>
      
      <button class="sidebar-button" onclick="navigateTo('./payments')">
        <svg class="sidebar-button-icon" viewBox="0 0 24 24">
          <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
        </svg>
        <span class="sidebar-button-text" href="./payments">See Payments</span>
      </button>
      
      <button class="sidebar-button" onclick="navigateTo('./availability')">
        <svg class="sidebar-button-icon" viewBox="0 0 24 24">
          <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
        </svg>
        <span class="sidebar-button-text" href="./availability">Availability</span>
      </button>
    </div>
  </div>

  <div class="container">
    <iframe src="https://my.spline.design/greetingrobot-Pvgs7b6nCSIeZjZlHiQznAPJ/"></iframe>
    <div class="welcome-text" id="welcomeText">Welcome to dashboard</div>
  </div>

<script>
    // Pass the driver name from Laravel to JavaScript
    const driverName = @json($name ?? 'Driver');
    const driverId = @json($id ?? '');
    const driverRole = @json($role ?? '');

    // Sidebar functionality
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('hamburger');

    function toggleSidebar() {
        sidebar.classList.toggle('open');
        sidebarToggle.classList.toggle('open');
        sidebarOverlay.classList.toggle('active');
        hamburger.classList.toggle('open');
    }

    sidebarToggle.addEventListener('click', toggleSidebar);
    sidebarOverlay.addEventListener('click', toggleSidebar);

    // Close sidebar on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            toggleSidebar();
        }
    });

    // Set welcome text with the driver's name
    document.getElementById('welcomeText').textContent = `Welcome to dashboard, ${driverName}!`;

    // Navigation helper
    function navigateTo(url) {
        window.location.href = url;
    }

    // Example API call using the driver's data
    let fetchedLink = '';
    // fetch('/api/getlink', {
    //     method: 'POST',
    //     headers: { 
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF protection
    //     },
    //     body: JSON.stringify({ 
    //         name: driverName, 
    //         id: driverId,
    //         role: driverRole 
    //     })
    // })
    // .then(res => res.json())
    // .then(data => {
    //     console.log("Fetched link:", data.link);
    //     fetchedLink = data.link;
    // })
    // .catch(err => console.error("Failed to fetch link:", err));

    // See your kid button (if applicable for driver dashboard)
    // document.getElementById('seeKidBtn').onclick = function() {
    //     if (fetchedLink) {
    //         window.open(fetchedLink, '_blank');
    //     } else {
    //         alert(`No valid link available. Please wait or try again. Link: ${fetchedLink}`);
    //     }
    // };
</script>

</body>
</html>