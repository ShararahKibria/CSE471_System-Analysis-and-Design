<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .button { padding:12px 20px; border:none; border-radius:12px; color:white; font-weight:bold; cursor:pointer; font-size:0.9rem; transition: all 0.3s ease; text-decoration:none; display:inline-block; text-align:center; }
        .button.reviews { background:linear-gradient(135deg, #3498db, #2980b9); }
        .button.hire { background:linear-gradient(135deg, #27ae60, #229954); }
        .button:hover { transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,0.3); }

        /* Fullscreen image */
        .popup { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.9); justify-content:center; align-items:center; z-index:9999; backdrop-filter:blur(5px); }
        .popup img { max-width:90%; max-height:90%; border-radius:15px; box-shadow:0 20px 60px rgba(0,0,0,0.5); animation:popupZoom 0.3s ease-out; }
        @keyframes popupZoom { 0% { transform:scale(0.5); opacity:0; } 100% { transform:scale(1); opacity:1; } }

        /* Hire form popup */
        .hire-popup { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:10000; backdrop-filter:blur(10px); }
        .hire-form { background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); padding:40px; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,0.3); max-width:450px; width:90%; animation:popupSlide 0.4s ease-out; }
        @keyframes popupSlide { 0% { transform:translateY(-30px) scale(0.9); opacity:0; } 100% { transform:translateY(0) scale(1); opacity:1; } }
        .hire-form h3 { color:#2c3e50; margin-bottom:25px; text-align:center; font-size:1.8rem; }
        .hire-form .form-group { margin-bottom:20px; }
        .hire-form label { display:block; margin-bottom:8px; color:#2c3e50; font-weight:600; font-size:1rem; }
        .hire-form input { width:100%; padding:15px; border:2px solid #e1e5e9; border-radius:12px; font-size:1rem; transition: all 0.3s ease; background:#f8f9fa; }
        .hire-form input:focus { outline:none; border-color:#667eea; background:white; box-shadow:0 0 0 3px rgba(102,126,234,0.1); transform:translateY(-2px); }
        .hire-form .button-group { display:flex; gap:15px; margin-top:30px; }
        .hire-form .btn { flex:1; padding:15px; border:none; border-radius:12px; font-size:1rem; font-weight:600; cursor:pointer; transition: all 0.3s ease; }
        .hire-form .btn-primary { background:linear-gradient(45deg, #667eea, #764ba2); color:white; }
        .hire-form .btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(102,126,234,0.3); }
        .hire-form .btn-secondary { background:#f8f9fa; color:#666; border:2px solid #e1e5e9; }
        .hire-form .btn-secondary:hover { background:#e9ecef; border-color:#dee2e6; }
        .driver-info { background:linear-gradient(45deg, #667eea, #764ba2); color:white; padding:20px; border-radius:15px; margin-bottom:25px; text-align:center; }
        .driver-info h4 { margin:0 0 10px 0; font-size:1.3rem; }
        .driver-info p { margin:0; opacity:0.9; }

        /* Loading state */
        .loading { text-align:center; width:100%; color:white; background:rgba(255,255,255,0.1); padding:40px; border-radius:15px; backdrop-filter:blur(10px); }
        .spinner { width:40px; height:40px; border:4px solid rgba(255,255,255,0.3); border-top:4px solid white; border-radius:50%; animation:spin 1s linear infinite; margin:0 auto 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Debug info */
        .debug-info { position:fixed; bottom:20px; left:20px; background:rgba(0,0,0,0.8); color:white; padding:15px; border-radius:10px; font-family:monospace; font-size:12px; max-width:300px; z-index:9999; display:none; }
        .debug-toggle { position:fixed; bottom:20px; right:20px; background:#3498db; color:white; padding:10px 15px; border:none; border-radius:8px; cursor:pointer; z-index:9999; }

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
            .debug-info { bottom:80px; left:10px; right:10px; max-width:none; }
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

<!-- Debug toggle -->
<button class="debug-toggle" onclick="toggleDebug()">Debug</button>

<!-- Debug info -->
<div class="debug-info" id="debugInfo">
    <strong>Debug Info:</strong><br>
    <span id="debugContent">No debug info yet</span>
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

<!-- Hire popup -->
<div class="hire-popup" id="hirePopup">
    <div class="hire-form">
        <div class="driver-info" id="selectedDriverInfo">
            <h4 id="selectedDriverName">Driver Name</h4>
            <p id="selectedDriverDetails">Driver Details</p>
        </div>
        
        <h3>🚗 Hire Driver</h3>
        
        <form id="hireForm">
            <div class="form-group">
                <label for="ownerName">Your Name</label>
                <input type="text" id="ownerName" name="owner_name" required placeholder="Enter your full name">
            </div>
            
            <div class="form-group">
                <label for="ownerId">Owner ID</label>
                <input type="text" id="ownerId" name="owner_id" required placeholder="Enter your ID number">
            </div>
            
            <div class="form-group">
                <label for="driverId">Driver ID</label>
                <input type="text" id="driverId" name="driver_id" readonly placeholder="Auto-filled">
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">💼 Submit Hire Request</button>
                <button type="button" class="btn btn-secondary" onclick="closeHirePopup()">❌ Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedDriverData = null;
    let debugMode = false;

    // Debug functions
    function toggleDebug() {
        debugMode = !debugMode;
        const debugInfo = document.getElementById('debugInfo');
        debugInfo.style.display = debugMode ? 'block' : 'none';
    }

    function addDebugInfo(message) {
        if (debugMode) {
            const debugContent = document.getElementById('debugContent');
            const timestamp = new Date().toLocaleTimeString();
            debugContent.innerHTML += `<br>[${timestamp}] ${message}`;
        }
        console.log(message); // Always log to console
    }

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

    // Show hire popup
    function showHirePopup(driver) {
        addDebugInfo(`Opening hire popup for driver: ${driver.name} (ID: ${driver.id})`);
        
        selectedDriverData = driver;
        document.getElementById('selectedDriverName').textContent = driver.name;
        document.getElementById('selectedDriverDetails').textContent = `${driver.license_type} • $${driver.expected_salary} • Rating: ${driver.rating}`;
        
        // Auto-fill driver ID
        document.getElementById('driverId').value = driver.id;
        
        document.getElementById('hirePopup').style.display = 'flex';
    }

    // Close hire popup
    function closeHirePopup() {
        document.getElementById('hirePopup').style.display = 'none';
        selectedDriverData = null;
        document.getElementById('hireForm').reset();
        addDebugInfo('Hire popup closed');
    }

    // Handle hire form submission
    document.getElementById('hireForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        addDebugInfo('Form submission started');
        
        if (!selectedDriverData) {
            alert('No driver selected');
            addDebugInfo('ERROR: No driver selected');
            return;
        }

        const formData = new FormData(this);
        const submitData = {
            driver_id: selectedDriverData.id,
            driver_name: selectedDriverData.name,
            owner_id: formData.get('owner_id'),
            owner_name: formData.get('owner_name')
        };

        addDebugInfo(`Submit data: ${JSON.stringify(submitData)}`);

        // Validate required fields
        if (!submitData.owner_name || !submitData.owner_id) {
            alert('Please fill in all required fields');
            addDebugInfo('ERROR: Missing required fields');
            return;
        }

        try {
            const submitButton = document.querySelector('.btn-primary');
            const originalText = submitButton.textContent;
            submitButton.textContent = '⏳ Submitting...';
            submitButton.disabled = true;

            addDebugInfo('Sending request to server...');

            // Get CSRF token
            let csrfToken = '';
            const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
            if (csrfMetaTag) {
                csrfToken = csrfMetaTag.getAttribute('content');
            }

            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };

            // Add CSRF token if available
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
                addDebugInfo(`Using CSRF token: ${csrfToken.substring(0, 10)}...`);
            } else {
                addDebugInfo('No CSRF token found - this might cause issues');
            }

            const response = await fetch('http://127.0.0.1:8000/hire-driver', {
                method: 'POST',
                headers: headers,
                mode: 'cors',
                credentials: 'same-origin', // Include cookies for CSRF
                body: JSON.stringify(submitData)
            });

            addDebugInfo(`Response status: ${response.status}`);
            addDebugInfo(`Response headers: ${JSON.stringify([...response.headers])}`);

            const result = await response.json();
            addDebugInfo(`Response data: ${JSON.stringify(result)}`);

            if (response.ok && result.success) {
                alert('✅ Hire request submitted successfully! Confirmation ID: ' + result.id);
                addDebugInfo(`SUCCESS: Confirmation ID ${result.id}`);
                closeHirePopup();
            } else {
                const errorMsg = result.error || result.message || 'Failed to submit hire request';
                alert('❌ Error: ' + errorMsg);
                addDebugInfo(`ERROR: ${errorMsg}`);
            }
        } catch (error) {
            console.error('Error submitting hire request:', error);
            addDebugInfo(`NETWORK ERROR: ${error.message}`);
            
            // Check if it's a CORS error
            if (error.message.includes('CORS') || error.message.includes('fetch')) {
                alert('❌ Connection error. Please check if the backend server is running on http://127.0.0.1:8000 and has CORS enabled.');
            } else {
                alert('❌ Network error. Please check your connection and try again.');
            }
        } finally {
            const submitButton = document.querySelector('.btn-primary');
            submitButton.textContent = '💼 Submit Hire Request';
            submitButton.disabled = false;
        }
    });

    // Close popup when clicking outside
    document.getElementById('hirePopup').addEventListener('click', function(e) {
        if (e.target === this) {
            closeHirePopup();
        }
    });

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
            addDebugInfo('Loading drivers from server...');
            
            const response = await fetch('http://127.0.0.1:8000/drivers', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                mode: 'cors'
            });
            
            addDebugInfo(`Drivers response status: ${response.status}`);
            
            if(!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            
            const drivers = await response.json();
            addDebugInfo(`Loaded ${drivers.length} drivers`);
            
            const container = document.getElementById('carList');
            container.innerHTML = '';
            
            drivers.forEach((driver,index) => {
                const card = document.createElement('div');
                card.className = 'card';
                const imageUrl = `/${driver.image}`;
                
                // Create a safe JSON string for onclick
                const driverJsonSafe = JSON.stringify(driver)
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
                
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
                           <a href="/cars/find_driver/review/${driver.id}" class="button reviews">📝 See Reviews</a>
                           <button class="button hire" onclick='showHirePopup(${JSON.stringify(driver)})'>💼 Hire</button>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
            
            setTimeout(() => { animateCards(); }, 100);
            
        } catch(err) {
            console.error(err);
            addDebugInfo(`Load drivers ERROR: ${err.message}`);
            
            document.getElementById('carList').innerHTML = `
                <div style="text-align:center; width:100%; color:white; background:rgba(255,255,255,0.1); padding:40px; border-radius:15px; backdrop-filter:blur(10px);">
                    <h3>⚠️ Unable to Load Drivers</h3>
                    <p>Error: ${err.message}</p>
                    <p>Please check your connection and try again.</p>
                    <button onclick="loadDrivers()" style="margin-top:15px; padding:10px 20px; background:#3498db; color:white; border:none; border-radius:8px; cursor:pointer;">🔄 Retry</button>
                </div>
            `;
        }
    }

    // Load on page load
    document.addEventListener('DOMContentLoaded', function() {
        addDebugInfo('Page loaded, starting driver load...');
        loadDrivers();
    });

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