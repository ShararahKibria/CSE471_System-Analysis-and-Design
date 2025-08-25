<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            max-width: 600px;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
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

        .form-group input:read-only {
            background: #f8f9fa;
            color: #6c757d;
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

        .submit-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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
            font-weight: 500;
            white-space: pre-line;
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

        .selected-mechanic-info {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: none;
        }

        .status-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            display: none;
        }

        .status-indicator.online {
            background: #28a745;
        }

        .status-indicator.offline {
            background: #dc3545;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .form-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="status-indicator" id="statusIndicator">🔄 Checking connection...</div>
    
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
                <h2 class="form-title">🛠️ Request Mechanic Service</h2>
                
                <div class="selected-mechanic-info" id="selectedMechanicInfo">
                    <h4 id="selectedMechanicName">Selected Mechanic</h4>
                    <p id="selectedMechanicDetails">Mechanic details</p>
                </div>
                
                <div class="alert" id="alert"></div>
                
                <form id="mechanicForm">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ownerName">👤 Your Name</label>
                            <input type="text" id="ownerName" name="ownerName" placeholder="Enter your full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ownerId">🆔 Your ID</label>
                            <input type="text" id="ownerId" name="ownerId" placeholder="Enter your ID number" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="mechanicId">🔧 Mechanic ID</label>
                            <input type="number" id="mechanicId" name="mechanicId" placeholder="Select from map" readonly required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mechanicName">👨‍🔧 Mechanic Name</label>
                            <input type="text" id="mechanicName" name="mechanicName" placeholder="Select from map" readonly required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="serviceDate">📅 Service Date</label>
                        <input type="date" id="serviceDate" name="serviceDate" required>
                    </div>
                    
                    <button type="submit" class="submit-btn" id="submitBtn">🚀 Request Service</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let map;
        let mechanics = [];
        let selectedMechanic = null;

        // Configuration - Update this to match your Laravel server
        const API_BASE_URL = 'http://127.0.0.1:8000';

        // Status indicator functions
        function showStatus(message, type) {
            const indicator = document.getElementById('statusIndicator');
            indicator.textContent = message;
            indicator.className = `status-indicator ${type}`;
            indicator.style.display = 'block';
            
            if (type === 'online') {
                setTimeout(() => {
                    indicator.style.display = 'none';
                }, 3000);
            }
        }

        // Test API connection
        async function testConnection() {
            try {
                const response = await fetch(`${API_BASE_URL}/mechanics`, {
                    method: 'HEAD',
                    mode: 'cors'
                });
                showStatus('✅ Connected to server', 'online');
                return true;
            } catch (error) {
                showStatus('❌ Server connection failed', 'offline');
                console.error('Connection test failed:', error);
                return false;
            }
        }

        // Initialize map
        function initMap() {
            console.log('Initializing map...');
            map = L.map('map').setView([23.8103, 90.4125], 12);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            console.log('Map initialized successfully');
        }

        // Fetch mechanics data with improved error handling
        async function fetchMechanics() {
            const loading = document.getElementById('loading');
            loading.style.display = 'block';
            
            try {
                console.log('Fetching mechanics from:', `${API_BASE_URL}/mechanics`);
                
                const response = await fetch(`${API_BASE_URL}/mechanics`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', Object.fromEntries(response.headers.entries()));
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }
                
                const data = await response.json();
                console.log('Received data:', data);
                
                if (Array.isArray(data)) {
                    mechanics = data;
                } else if (data.data && Array.isArray(data.data)) {
                    mechanics = data.data;
                } else {
                    throw new Error('Invalid data format received');
                }
                
                console.log(`Loaded ${mechanics.length} mechanics`);
                
                if (mechanics.length > 0) {
                    displayMechanics();
                    updateMechanicsInfo();
                    showAlert(`✅ Successfully loaded ${mechanics.length} mechanics!`, 'success');
                } else {
                    showAlert('⚠️ No mechanics data found. Please check your database.', 'error');
                }
                
            } catch (error) {
                console.error('Error fetching mechanics:', error);
                showAlert(`❌ Failed to load mechanics: ${error.message}\n\nPlease check:\n1. Laravel server is running\n2. Database connection is working\n3. CORS is configured\n4. API URL is correct: ${API_BASE_URL}`, 'error');
            } finally {
                loading.style.display = 'none';
            }
        }

        // Create custom mechanic icon
        function createMechanicIcon(isAvailable = true) {
            const iconColor = isAvailable ? '#28a745' : '#dc3545';
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

        // Display mechanics on map
        function displayMechanics() {
            console.log('Displaying mechanics on map...');
            
            mechanics.forEach((mechanic) => {
                const lat = parseFloat(mechanic.latitude);
                const lng = parseFloat(mechanic.longitude);
                
                if (!isNaN(lat) && !isNaN(lng) && Math.abs(lat) > 0.001 && Math.abs(lng) > 0.001) {
                    const isAvailable = mechanic.status == 0;
                    const customIcon = createMechanicIcon(isAvailable);
                    
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
                                <p style="margin: 5px 0;"><strong>Status:</strong> ${statusText}</p>
                            </div>
                            
                            ${isAvailable ? `
                                <button onclick="selectMechanic(${mechanic.id}, '${mechanic.name.replace(/'/g, "\\'")}', '${mechanic.specialty}', '${mechanic.phone}')" 
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
                    console.log(`✅ Added marker for ${mechanic.name}`);
                } else {
                    console.warn(`❌ Invalid coordinates for ${mechanic.name}: ${lat}, ${lng}`);
                }
            });
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
        function selectMechanic(id, name, specialty, phone) {
            console.log(`Selecting mechanic: ${name} (ID: ${id})`);
            
            selectedMechanic = { id, name, specialty, phone };
            
            document.getElementById('mechanicId').value = id;
            document.getElementById('mechanicName').value = name;
            
            const selectedInfo = document.getElementById('selectedMechanicInfo');
            const selectedName = document.getElementById('selectedMechanicName');
            const selectedDetails = document.getElementById('selectedMechanicDetails');
            
            selectedName.textContent = `🔧 ${name}`;
            selectedDetails.textContent = `${specialty} • ${phone}`;
            selectedInfo.style.display = 'block';
            
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('serviceDate').min = today;
            
            showAlert('✅ Mechanic selected! Please fill in your details and choose a service date.', 'success');
            
            document.getElementById('mechanicForm').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }

        // Handle form submission
        document.getElementById('mechanicForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitData = {
                mechanic_id: parseInt(formData.get('mechanicId')),
                mechanic_name: formData.get('mechanicName'),
                owner_id: formData.get('ownerId'),
                owner_name: formData.get('ownerName'),
                service_date: formData.get('serviceDate')
            };
            
            console.log('Submitting form data:', submitData);
            
            if (!submitData.owner_name || !submitData.owner_id || !submitData.mechanic_id || !submitData.service_date) {
                showAlert('❌ Please fill in all required fields.', 'error');
                return;
            }
            
            // Validate service date
            const serviceDate = new Date(submitData.service_date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (serviceDate < today) {
                showAlert('❌ Service date must be today or in the future.', 'error');
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = '⏳ Submitting Request...';
            submitBtn.disabled = true;
            
            try {
                console.log('Sending API request to:', `${API_BASE_URL}/mechanic-confirmation`);
                
                const response = await fetch(`${API_BASE_URL}/mechanic-confirmation`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(submitData)
                });
                
                console.log('Form submission response status:', response.status);
                
                const responseData = await response.json();
                console.log('Form submission response data:', responseData);
                
                if (!response.ok) {
                    throw new Error(responseData.message || responseData.error || `HTTP ${response.status}`);
                }
                
                // Show success message
                showAlert(`✅ ${responseData.message || 'Service request submitted successfully!'}

📋 Request Summary:
🆔 Request ID: ${responseData.request_id || 'N/A'}
👤 Owner: ${submitData.owner_name}
🔧 Mechanic: ${submitData.mechanic_name} (ID: ${submitData.mechanic_id})
📅 Service Date: ${submitData.service_date}
📋 Status: Pending
💰 Payment: Pending`, 'success');
                
                // Reset form
                document.getElementById('mechanicForm').reset();
                selectedMechanic = null;
                document.getElementById('selectedMechanicInfo').style.display = 'none';
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
            } catch (error) {
                console.error('Error submitting form:', error);
                showAlert(`❌ Failed to submit service request: ${error.message}

Troubleshooting:
1. Check if Laravel server is running
2. Verify database connection
3. Check server logs for errors
4. Ensure API endpoint is working`, 'error');
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });

        // Show alert messages
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            alert.textContent = message;
            alert.className = `alert ${type}`;
            alert.style.display = 'block';
            
            if (type === 'success') {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 10000);
            }
        }

        // Initialize everything
        document.addEventListener('DOMContentLoaded', async function() {
            console.log('Page loaded, initializing...');
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('serviceDate').min = today;
            
            // Test connection first
            await testConnection();
            
            // Initialize map
            initMap();
            
            // Fetch mechanics data
            await fetchMechanics();
        });

        // Add window global function for onclick handlers
        window.selectMechanic = selectMechanic;
    </script>
</body>
</html>