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

    /* Navbar */
    .navbar {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      background-color: black;
      z-index: 10;
    }

    .navbar button {
      color: #7e22ce; /* purple-800 */
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      background: none;
      border: 1px solid #7e22ce;
      padding: 0.5rem 1rem;
      cursor: pointer;
      transition: 0.3s;
    }

    .navbar button:hover {
      background-color: #7e22ce;
      color: white;
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
    }

    @keyframes fadeInSlide {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
  </style>
</head>
<body>

  <div class="navbar">
    <button onclick="navigateTo('/Dashboard/Findsitter')">Find a baby sitter</button>
    <button id="seeKidBtn">See your kid</button>
    <button onclick="navigateTo('./Recording')">See your sitter's previous experience</button>
    <button onclick="navigateTo('./Payment')">Renew Subscription</button>
  </div>

  <div class="container">
    <iframe src="https://my.spline.design/greetingrobot-Pvgs7b6nCSIeZjZlHiQznAPJ/"></iframe>
    <div class="welcome-text" id="welcomeText">Welcome to dashboard</div>
  </div>

  <script>
    // Get user info from localStorage
    const name = localStorage.getItem('userName') || '';
    const id = localStorage.getItem('userId') || '';

    // Set welcome text
    document.getElementById('welcomeText').textContent = `Welcome to dashboard ${name}`;

    // Navigation helper
    function navigateTo(url) {
      window.location.href = url;
    }

    // Fetch link from API
    // let fetchedLink = '';
    // fetch('http://localhost:3001/api/getlink', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify({ name, id })
    // })
    // .then(res => res.json())
    // .then(data => {
    //   console.log("Fetched link:", data.link);
    //   fetchedLink = data.link;
    // })
    // .catch(err => console.error("Failed to fetch link:", err));

    // See your kid button
    document.getElementById('seeKidBtn').onclick = function() {
      if (fetchedLink) {
        window.open(fetchedLink, '_blank');
      } else {
        alert(`No valid link available. Please wait or try again. Link: ${fetchedLink}`);
      }
    };
  </script>

</body>
</html>
