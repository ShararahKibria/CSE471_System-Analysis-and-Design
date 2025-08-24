<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Drivers - Emergency Cars</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; overflow-x: hidden; transition: all 0.5s ease; position: relative; }

        /* Background gradients */
        .bg-gradient-sunset { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-ocean { background: linear-gradient(135deg, #667db6 0%, #0082c8 100%); }
        .bg-gradient-forest { background: linear-gradient(135deg, #74b9ff 0%, #6c5ce7 100%); }
        .bg-gradient-luxury { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); }
        .bg-gradient-warm { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }

        /* Animated background shapes */
        .bg-shapes { position: fixed; top:0; left:0; width:100%; height:100%; z-index:-1; overflow:hidden; }
        .shape { position:absolute; border-radius:50%; background: rgba(255,255,255,0.1); animation: float 6s infinite ease-in-out; }
        .shape:nth-child(1) { width:100px; height:100px; top:10%; left:10%; animation-delay:0s; }
        .shape:nth-child(2) { width:150px; height:150px; top:60%; right:10%; animation-delay:2s; }
        .shape:nth-child(3) { width:80px; height:80px; bottom:10%; left:50%; animation-delay:4s; }

        @keyframes float {
            0%,100% { transform: translateY(0px) rotate(0deg); opacity:0.7; }
            50% { transform: translateY(-20px) rotate(180deg); opacity:1; }
        }

        /* Header */
        .header { text-align:center; padding:60px 20px; color:white; position:relative; z-index:10; }
        .header h1 { font-size:3.5rem; margin-bottom:20px; text-shadow:2px 2px 8px rgba(0,0,0,0.3); animation:slideInDown 1s ease-out; }
        .tagline { font-size:1.3rem; margin-bottom:30px; opacity:0.9; animation:slideInUp 1s ease-out 0.2s both; }

        /* Features */
        .features { display:flex; justify-content:center; gap:40px; flex-wrap:wrap; margin-top:30px; animation:slideInUp 1s ease-out 0.4s both; }
        .feature { text-align:center; padding:20px; background: rgba(255,255,255,0.15); border-radius:15px; backdrop-filter: blur(10px); border:1px solid rgba(255,255,255,0.2); min-width:200px; transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .feature:hover { transform:translateY(-5px); box-shadow:0 10px 30px rgba(255,255,255,0.2); }
        .feature-icon { font-size:2.5rem; margin-bottom:15px; display:block; }
        .feature h3 { font-size:1.2rem; margin-bottom:10px; }
        .feature p { font-size:0.95rem; opacity:0.9; }

        /* Background selector */
        .bg-selector { position:fixed; top:20px; right:20px; z-index:1000; display:flex; gap:10px; background:rgba(255,255,255,0.1); padding:15px; border-radius:25px; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.2); }
        .bg-option { width:30px; height:30px; border-radius:50%; cursor:pointer; border:2px solid rgba(255,255,255,0.3); transition: all 0.3s ease; }
        .bg-option:hover { transform:scale(1.2); border-color:white; }
        .bg-option.active { border-color:white; box-shadow:0 0 15px rgba(255,255,255,0.5); }

        /* Cards */
        .main-content { padding:40px 20px; position:relative; z-index:10; }
        .card-container { display:grid; grid-template-columns: repeat(auto-fill, minmax(350px,1fr)); gap:30px; max-width:1400px; margin:0 auto; }
        .card { background: rgba(255,255,255,0.95); border-radius:20px; box-shadow:0 10px 40px rgba(0,0,0,0.15); overflow:hidden; transform:translateY(50px); opacity:0; transition:all 0.4s ease; backdrop-filter: blur(10px); border:1px solid rgba(255,255,255,0.3); }
        .card.animate { transform:translateY(0); opacity:1; }
        .card:hover { transform:translateY(-15px); box-shadow:0 20px 60px rgba(0,0,0,0.25); }
        .card img { width:100%; height:220px; object-fit:cover; cursor:pointer; transition: transform 0.4s ease; }
        .card:hover img { transform:scale(1.05); }
        .card-content { padding:25px; }
        .card-content h3 { margin:0 0 15px 0; color:#2c3e50; font-size:1.4rem; font-weight:bold; }
        .card-content p { margin:8px 0; color:#555; font-size:0.95rem; line-height:1.5; }
        .card-content p strong { color:#2c3e50; }
        .button-group { display:flex; justify-content:flex-start; gap:12px; margin-top:20px; }
        .button { padding:12px 20px; border:none; border-radius:12px; color:white; font-weight:bold; cursor:pointer; font-size:0.9rem; transition: all 0.3s ease; background:linear-gradient(135deg, #3498db, #2980b9); }
        .button:hover { transform:translateY(-2px); box-shadow:0 5px 15px rgba(52,152,219,0.4); }

        /* Fullscreen image */
        .popup { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.9); justify-content:center; align-items:center; z-index:9999; backdrop-filter:blur(5px); }
        .popup img { max-width:90%; max-height:90%; border-radius:15px; box-shadow:0 20px 60px rgba(0,0,0,0.5); animation:popupZoom 0.3s ease-out; }
        @keyframes popupZoom { 0% { transform:scale(0.5); opacity:0; } 100% { transform:scale(1); opacity:1; } }

        /* Animations */
        @keyframes slideInDown { 0% { transform:translateY(-50px); opacity:0; } 100% { transform:translateY(0); opacity:1; } }
        @keyframes slideInUp { 0% { transform:translateY(50px); opacity:0; } 100% { transform:translateY(0); opacity:1; } }

        /* Responsive */
        @media (max-width:768px) {
            .header h1 { font-size:2.5rem; }
            .tagline { font-size:1.1rem; }
            .features { gap:20px; }
            .feature { min-width:150px; padding:15px; }
            .card-container { grid-template-columns:1fr; }
            .bg-selector { top:10px; right:10px; padding:10px; }
        }
    </style>
</head>
<body class="bg-gradient-sunset">

<!-- Animated shapes -->
<div class="bg-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
</div>

<!-- Background selector -->
<div class="bg-selector">
    <div class="bg-option active" data-bg="bg-gradient-sunset" style="background: linear-gradient(135deg, #667eea, #764ba2);"></div>
    <div class="bg-option" data-bg="bg-gradient-ocean" style="background: linear-gradient(135deg, #667db6, #0082c8);"></div>
    <div class="bg-option" data-bg="bg-gradient-forest" style="background: linear-gradient(135deg, #74b9ff, #6c5ce7);"></div>
    <div class="bg-option" data-bg="bg-gradient-luxury" style="background: linear-gradient(135deg, #2c3e50, #34495e);"></div>
    <div class="bg-option" data-bg="bg-gradient-warm" style="background: linear-gradient(135deg, #fa709a, #fee140);"></div>
</div>

<!-- Header -->
<div class="header">
    <h1>👨‍✈️ Find Drivers</h1>
    <p class="tagline">Select your preferred driver instantly</p>

    <div class="features">
        <div class="feature">
            <span class="feature-icon">🛡️</span>
            <h3>Verified & Trusted</h3>
            <p>All drivers are fully verified for safety and reliability</p>
        </div>
        <div class="feature">
            <span class="feature-icon">💵</span>
            <h3>Fair Salary</h3>
            <p>Competitive expected salary with transparent terms</p>
        </div>
        <div class="feature">
            <span class="feature-icon">⭐</span>
            <h3>Top Rated</h3>
            <p>Check ratings to choose the best driver for your needs</p>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="main-content">
    <div class="card-container" id="carList">
        <div class="loading">
            <div class="spinner"></div>
            Loading available drivers...
        </div>
    </div>
</div>

<!-- Image popup -->
<div class="popup" id="imagePopup" onclick="this.style.display='none'">
    <img id="popupImage" src="" alt="Driver Image">
</div>

<script>
    // Background selector
    document.querySelectorAll('.bg-option').forEach(option => {
        option.addEventListener('click', () => {
            document.querySelectorAll('.bg-option').forEach(opt => opt.classList.remove('active'));
            option.classList.add('active');
            document.body.className = option.getAttribute('data-bg');
        });
    });

    // Show image popup
    function showImage(url) {
        document.getElementById('popupImage').src = url;
        document.getElementById('imagePopup').style.display = 'flex';
    }

    // Animate cards
    function animateCards() {
        const cards = document.querySelectorAll('.card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry,index) => {
                if(entry.isIntersecting){
                    setTimeout(()=>{entry.target.classList.add('animate');}, index*150);
                }
            });
        }, { threshold: 0.1 });
        cards.forEach(card => observer.observe(card));
    }

    // Load drivers
    async function loadDrivers() {
        try {
            const response = await fetch('http://127.0.0.1:8000/drivers');
            if(!response.ok) throw new Error('Network error');
            const drivers = await response.json();
            const container = document.getElementById('carList');
            container.innerHTML = '';
            drivers.forEach((driver,index)=>{
                const card = document.createElement('div');
                card.className = 'card';
                const imageUrl = `/${driver.image}`;
                card.innerHTML = `
                    <img src="${imageUrl}" alt="${driver.name}" 
                        onerror="this.src='https://via.placeholder.com/300x220/667eea/ffffff?text=👨‍✈️+Driver+Image'" 
                        onclick="showImage('${imageUrl}')">
                    <div class="card-content">
                        <h3>${driver.name}</h3>
                        <p><strong>📞 Phone:</strong> ${driver.phone}</p>
                        <p><strong>🪪 License Type:</strong> ${driver.license_type}</p>
                        <p><strong>📍 Address:</strong> ${driver.address}</p>
                        <p><strong>💵 Expected Salary:</strong> $${driver.expected_salary}</p>
                        <p><strong>⭐ Rating:</strong> ${driver.rating}</p>
                        <div class="button-group">
                           <a href="/cars/find_driver/review/${driver.id}" class="button self-service">📝 See Reviews</a>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
            setTimeout(()=>{animateCards();},100);
        } catch(err){
            console.error(err);
            document.getElementById('carList').innerHTML = `
                <div style="text-align:center; width:100%; color:white; background:rgba(255,255,255,0.1); padding:40px; border-radius:15px; backdrop-filter:blur(10px);">
                    <h3>⚠️ Unable to Load Drivers</h3>
                    <p>Please check your connection and try again.</p>
                    <button onclick="loadDrivers()" style="margin-top:15px; padding:10px 20px; background:#3498db; color:white; border:none; border-radius:8px; cursor:pointer;">🔄 Retry</button>
                </div>
            `;
        }
    }

    function seeReviews(driverId) {
        alert(`Reviews for Driver ID: ${driverId} — To be implemented`);
    }

    // Load on page load
    loadDrivers();

    // Extra interactivity: shapes follow mouse
    document.addEventListener('mousemove',(e)=>{
        const shapes=document.querySelectorAll('.shape');
        const mouseX = e.clientX/window.innerWidth;
        const mouseY = e.clientY/window.innerHeight;
        shapes.forEach((shape,index)=>{
            const speed=(index+1)*0.5;
            const x = mouseX*speed;
            const y = mouseY*speed;
            shape.style.transform=`translate(${x}px, ${y}px)`;
        });
    });
</script>
</body>
</html>
