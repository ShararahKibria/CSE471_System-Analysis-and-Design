<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Mechanics</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        #map {
            height: 500px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
        }

        .form-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
            color: #667eea;
            font-size: 1.1rem;
        }

        .loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #667eea;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .mechanics-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .mechanics-count {
            font-size: 1.2rem;
            color: #667eea;
            font-weight: bold;
        }

        /* Custom marker animations and styles */
        .custom-mechanic-marker {
            background: transparent !important;
            border: none !important;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            }
            50% {
                transform: scale(1.1);
                box-shadow: 0 6px 12px rgba(0,0,0,0.4);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            }
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .leaflet-popup-tip {
            background: white !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Find Mechanics</h1>
            <p>Locate and request services from nearby mechanics</p>
        </div>
        
        <div class="content">
            <div class="loading" id="loading">Loading mechanics...</div>
            
            <div class="mechanics-info" id="mechanicsInfo" style="display: none;">
                <div class="mechanics-count" id="mechanicsCount">0 mechanics found</div>
            </div>
            
            <div id="map"></div>
            
            <div class="form-container">
                <h2 class="form-title">Request Mechanic Service</h2>
                
                <div class="alert" id="alert"></div>
                
                <form id="mechanicForm">
                    <div class="form-group">
                        <label for="mechanicId">Mechanic ID</label>
                        <input type="number" id="mechanicId" name="mechanicId" placeholder="Enter mechanic ID" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="mechanicName">Mechanic Name</label>
                        <input type="text" id="mechanicName" name="mechanicName" placeholder="Enter mechanic name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="serviceDate">Service Date</label>
                        <input type="date" id="serviceDate" name="serviceDate" required>
                    </div>
                    
                    <button type="submit" class="submit-btn">Request Service</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let map;
        let mechanics = [];

        // Initialize map
        function initMap() {
            map = L.map('map').setView([23.8103, 90.4125], 12); // Dhaka coordinates
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        }

        // Fetch mechanics data
        async function fetchMechanics() {
            const loading = document.getElementById('loading');
            loading.style.display = 'block';
            
            try {
                // Fetch from your Laravel API endpoint with real data
                const response = await fetch('http://127.0.0.1:8000/mechanics');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                mechanics = await response.json();
                
                console.log('Fetched mechanics data:', mechanics); // Debug log
                
                if (mechanics && mechanics.length > 0) {
                    displayMechanics();
                    updateMechanicsInfo();
                    addLegend();
                } else {
                    showAlert('No mechanics data found.', 'error');
                }
                
            } catch (error) {
                console.error('Error fetching mechanics:', error);
                showAlert(`Failed to load mechanics data: ${error.message}`, 'error');
            } finally {
                loading.style.display = 'none';
            }
        }

        // Create custom mechanic icon
        function createMechanicIcon(isAvailable = true) {
            const iconColor = isAvailable ? '#28a745' : '#dc3545'; // Green for available, red for busy
            const iconHtml = `
                <div style="
                    width: 40px;
                    height: 40px;
                    background: ${iconColor};
                    border: 3px solid white;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                    font-size: 20px;
                    animation: pulse 2s infinite;
                ">
                    🔧
                </div>
            `;
            
            return L.divIcon({
                html: iconHtml,
                className: 'custom-mechanic-marker',
                iconSize: [40, 40],
                iconAnchor: [20, 20],
                popupAnchor: [0, -20]
            });
        }

        // Display mechanics on map using their exact coordinates
        function displayMechanics() {
            console.log('Displaying mechanics on map...'); // Debug log
            
            mechanics.forEach((mechanic, index) => {
                console.log(`Processing mechanic ${index + 1}:`, mechanic); // Debug log
                
                // Parse latitude and longitude from your JSON data structure
                const lat = parseFloat(mechanic.latitude);
                const lng = parseFloat(mechanic.longitude);
                
                console.log(`Mechanic: ${mechanic.name}, Lat: ${lat}, Lng: ${lng}`); // Debug log
                
                // Validate coordinates
                if (!isNaN(lat) && !isNaN(lng) && lat !== 1 && lng !== 1) {
                    const isAvailable = mechanic.status == 0; // status 0 = available, 1 = busy
                    const customIcon = createMechanicIcon(isAvailable);
                    
                    // Create marker at exact coordinates from your data
                    const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
                    
                    const statusText = isAvailable ? 
                        '<span style="color: #28a745; font-weight: bold;">✅ Available</span>' : 
                        '<span style="color: #dc3545; font-weight: bold;">🔴 Busy</span>';
                    
                    const popupContent = `
                        <div style="min-width: 220px; font-family: Arial, sans-serif;">
                            <div style="text-align: center; margin-bottom: 15px;">
                                <div style="font-size: 30px; margin-bottom: 5px;">🔧</div>
                                <h3 style="margin: 0; color: #333; font-size: 18px;">${mechanic.name}</h3>
                            </div>
                            
                            <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                                <p style="margin: 5px 0;"><strong>🆔 ID:</strong> ${mechanic.id}</p>
                                <p style="margin: 5px 0;"><strong>📞 Phone:</strong> ${mechanic.phone}</p>
                                <p style="margin: 5px 0;"><strong>⚙️ Specialty:</strong> ${mechanic.specialty}</p>
                                <p style="margin: 5px 0;"><strong>📍 Address:</strong> ${mechanic.address}</p>
                                <p style="margin: 5px 0;"><strong>📍 Coordinates:</strong> ${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                                <p style="margin: 5px 0;"><strong>Status:</strong> ${statusText}</p>
                            </div>
                            
                            ${isAvailable ? `
                                <button onclick="selectMechanic(${mechanic.id}, '${mechanic.name.replace(/'/g, "\\'")}')" 
                                        style="width: 100%; margin-top: 10px; padding: 10px; background: linear-gradient(45deg, #28a745, #20c997); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 14px;">
                                    🛠️ Select This Mechanic
                                </button>
                            ` : `
                                <div style="width: 100%; margin-top: 10px; padding: 10px; background: #6c757d; color: white; border-radius: 6px; text-align: center; font-weight: bold;">
                                    ⏰ Currently Busy
                                </div>
                            `}
                        </div>
                    `;
                    
                    marker.bindPopup(popupContent);
                    
                    // Add hover effect
                    marker.on('mouseover', function() {
                        this.openPopup();
                    });
                    
                    console.log(`✅ Added marker for ${mechanic.name} at [${lat}, ${lng}]`); // Debug log
                } else {
                    console.warn(`❌ Invalid coordinates for mechanic: ${mechanic.name} - Lat: ${lat}, Lng: ${lng}`); // Debug log
                }
            });
            
            console.log(`Total markers added: ${document.querySelectorAll('.custom-mechanic-marker').length}`); // Debug log
        }

        // Add legend to the map
        function addLegend() {
            const legend = L.control({ position: 'bottomright' });
            
            legend.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'legend');
                div.innerHTML = `
                    <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); font-family: Arial, sans-serif;">
                        <h4 style="margin: 0 0 10px 0; color: #333; font-size: 14px;">🗺️ Legend</h4>
                        <div style="display: flex; align-items: center; margin: 5px 0;">
                            <div style="width: 20px; height: 20px; background: #28a745; border-radius: 50%; margin-right: 8px; border: 2px solid white;"></div>
                            <span style="font-size: 12px;">Available Mechanic</span>
                        </div>
                        <div style="display: flex; align-items: center; margin: 5px 0;">
                            <div style="width: 20px; height: 20px; background: #dc3545; border-radius: 50%; margin-right: 8px; border: 2px solid white;"></div>
                            <span style="font-size: 12px;">Busy Mechanic</span>
                        </div>
                    </div>
                `;
                return div;
            };
            
            legend.addTo(map);
        }

        // Update mechanics info
        function updateMechanicsInfo() {
            const mechanicsInfo = document.getElementById('mechanicsInfo');
            const mechanicsCount = document.getElementById('mechanicsCount');
            
            const availableCount = mechanics.filter(m => m.status == 0).length;
            const busyCount = mechanics.length - availableCount;
            
            mechanicsCount.innerHTML = `
                <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                    <span>📍 ${mechanics.length} total mechanics</span>
                    <span style="color: #28a745;">✅ ${availableCount} available</span>
                    <span style="color: #dc3545;">🔴 ${busyCount} busy</span>
                </div>
            `;
            mechanicsInfo.style.display = 'block';
        }

        // Select mechanic from map
        function selectMechanic(id, name) {
            document.getElementById('mechanicId').value = id;
            document.getElementById('mechanicName').value = name;
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('serviceDate').min = today;
            
            showAlert('Mechanic selected! Please choose a service date.', 'success');
        }

        // Handle form submission
        document.getElementById('mechanicForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const mechanicId = document.getElementById('mechanicId').value;
            const mechanicName = document.getElementById('mechanicName').value;
            const serviceDate = document.getElementById('serviceDate').value;
            
            if (!mechanicId || !mechanicName || !serviceDate) {
                showAlert('Please fill in all fields.', 'error');
                return;
            }
            
            // Get user data (you might store this differently in Laravel)
            const userId = localStorage.getItem('userId') || 'guest';
            const userName = localStorage.getItem('userName') || 'Guest User';
            
            try {
                const response = await fetch('http://127.0.0.1:8000/api/confirm-mechanic', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        // Add CSRF token if needed
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        mechanicId: mechanicId,
                        mechanicName: mechanicName,
                        userId: userId,
                        userName: userName,
                        serviceDate: serviceDate
                    })
                });
                
                if (response.ok) {
                    showAlert('Service request submitted successfully!', 'success');
                    document.getElementById('mechanicForm').reset();
                } else {
                    throw new Error('Server error');
                }
                
            } catch (error) {
                console.error('Error submitting request:', error);
                showAlert('Something went wrong. Please try again.', 'error');
            }
        });

        // Show alert messages
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            alert.textContent = message;
            alert.className = `alert ${type}`;
            alert.style.display = 'block';
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }

        // Set minimum date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('serviceDate').min = today;
        });

        // Initialize everything
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            fetchMechanics();
        });
    </script>
</body>
</html>