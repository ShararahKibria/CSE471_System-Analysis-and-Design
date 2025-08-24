<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Mechanic - RACECAR</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.03)"/><circle cx="10" cy="90" r="0.5" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            z-index: -1;
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-tool {
            position: absolute;
            font-size: 40px;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        .floating-tool:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-tool:nth-child(2) {
            top: 30%;
            right: 20%;
            animation-delay: 5s;
        }

        .floating-tool:nth-child(3) {
            bottom: 20%;
            left: 15%;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(5deg); }
            50% { transform: translateY(-10px) rotate(-3deg); }
            75% { transform: translateY(-15px) rotate(2deg); }
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            position: relative;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(30deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(30deg); }
        }

        .header h1 {
            color: white;
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 800;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
        }

        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1em;
            position: relative;
            z-index: 2;
        }

        .form-container {
            padding: 50px;
        }

        .success-message, .error-message {
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
            font-size: 1.1em;
            position: relative;
            overflow: hidden;
            animation: successPulse 0.6s ease-out;
        }

        .success-message {
            background: linear-gradient(135deg, #00b894, #00a085);
            color: white;
        }

        .error-message {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        @keyframes successPulse {
            0% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }

        .success-message::before {
            content: '✨';
            position: absolute;
            left: 20px;
            font-size: 1.5em;
        }

        .error-message::before {
            content: '⚠️';
            position: absolute;
            left: 20px;
            font-size: 1.5em;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .form-section {
            background: rgba(255, 255, 255, 0.7);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .form-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: #ff6b35;
        }

        .section-title {
            color: #2c3e50;
            font-size: 1.4em;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            font-size: 1.5em;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
            font-size: 0.95em;
            letter-spacing: 0.5px;
        }

        .input-container {
            position: relative;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid rgba(52, 73, 94, 0.1);
            border-radius: 12px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            transform: translateY(-2px);
            background: white;
        }

        .form-group input:valid {
            border-color: #00b894;
        }

        .submit-section {
            grid-column: 1 / -1;
            text-align: center;
            margin-top: 20px;
        }

        .submit-btn {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            padding: 18px 50px;
            border: none;
            border-radius: 50px;
            font-size: 1.1em;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 107, 53, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .specialty-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .specialty-chip {
            background: rgba(255, 107, 53, 0.1);
            color: #ff6b35;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            border: 1px solid rgba(255, 107, 53, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .specialty-chip:hover {
            background: #ff6b35;
            color: white;
            transform: translateY(-2px);
        }

        .rating-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .star-rating {
            display: flex;
            gap: 5px;
        }

        .star {
            font-size: 24px;
            color: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .star.active,
        .star:hover {
            color: #ffd700;
            transform: scale(1.1);
        }

        .location-btn {
            background: rgba(255, 107, 53, 0.1);
            color: #ff6b35;
            border: 2px solid rgba(255, 107, 53, 0.2);
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            margin-top: 10px;
        }

        .location-btn:hover {
            background: #ff6b35;
            color: white;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #bdc3c7;
            font-size: 18px;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus ~ .input-icon,
        .form-group select:focus ~ .input-icon,
        .form-group textarea:focus ~ .input-icon {
            color: #ff6b35;
        }

        .validation-errors {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .validation-errors ul {
            margin: 0;
            padding-left: 20px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 20px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .form-container {
                padding: 30px 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .form-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-tool">🔧</div>
        <div class="floating-tool">🔩</div>
        <div class="floating-tool">⚙️</div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Register as Mechanic</h1>
            <p>Join our expert mechanic network - Help drivers keep their cars running smoothly</p>
        </div>

        <div class="form-container">
            <!-- Success Message -->
            @if(session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="validation-errors">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/cars/add_mechanic" id="registrationForm">
                @csrf
                <div class="form-grid">
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">👤</span>
                            Personal Information
                        </h3>

                        <div class="form-group">
                            <label for="name">Full Name / Workshop Name</label>
                            <div class="input-container">
                                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name or workshop name" required>
                                <div class="input-icon">👤</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="input-container">
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Your contact number" required>
                                <div class="input-icon">📱</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-container">
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Your email address" required>
                                <div class="input-icon">📧</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-container">
                                <input type="password" id="password" name="password" placeholder="Create a secure password" required>
                                <div class="input-icon">🔒</div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Details -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">🔧</span>
                            Professional Details
                        </h3>

                        <div class="form-group">
                            <label for="specialty">Specialty</label>
                            <div class="input-container">
                                <textarea id="specialty" name="specialty" placeholder="Describe your expertise, certifications, and services offered..." required>{{ old('specialty') }}</textarea>
                                <div class="input-icon">🛠️</div>
                            </div>
                            <div class="specialty-chips">
                                <span class="specialty-chip" onclick="addSpecialty('🚗 Car Repair')">🚗 Car Repair</span>
                                <span class="specialty-chip" onclick="addSpecialty('🔋 Electrical')">🔋 Electrical</span>
                                <span class="specialty-chip" onclick="addSpecialty('🛞 Tire Service')">🛞 Tire Service</span>
                                <span class="specialty-chip" onclick="addSpecialty('🔧 Engine Repair')">🔧 Engine Repair</span>
                                <span class="specialty-chip" onclick="addSpecialty('🛡️ Body Work')">🛡️ Body Work</span>
                                <span class="specialty-chip" onclick="addSpecialty('🚙 SUV Specialist')">🚙 SUV Specialist</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Availability Status</label>
                            <select id="status" name="status" required>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Available - Ready for service calls</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Offline - Not taking requests</option>
                                <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Busy - Currently working</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Professional Rating</label>
                            <div class="rating-container">
                                <div class="star-rating" id="starRating">
                                    <span class="star" data-rating="1">★</span>
                                    <span class="star" data-rating="2">★</span>
                                    <span class="star" data-rating="3">★</span>
                                    <span class="star" data-rating="4">★</span>
                                    <span class="star" data-rating="5">★</span>
                                </div>
                                <span id="ratingText">No rating</span>
                            </div>
                            <input type="hidden" id="rating" name="rating" value="{{ old('rating', 0) }}">
                        </div>
                    </div>

                    <!-- Location & Workshop -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">📍</span>
                            Workshop Location
                        </h3>

                        <div class="form-group">
                            <label for="address">Workshop Address</label>
                            <div class="input-container">
                                <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Enter your workshop address" required>
                                <div class="input-icon">🏠</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <div class="input-container">
                                <input type="number" id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="0.000000" step="0.000001" min="-90" max="90">
                                <div class="input-icon">🌐</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <div class="input-container">
                                <input type="number" id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="0.000000" step="0.000001" min="-180" max="180">
                                <div class="input-icon">🌐</div>
                            </div>
                        </div>

                        <button type="button" class="location-btn" onclick="getCurrentLocation()">
                            📍 Get Workshop Location
                        </button>
                    </div>

                    <!-- Submit Section -->
                    <div class="submit-section">
                        <button type="submit" class="submit-btn">
                            Join Our Mechanic Network
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add specialty to textarea
        function addSpecialty(specialty) {
            const textarea = document.getElementById('specialty');
            const currentValue = textarea.value.trim();
            
            if (currentValue && !currentValue.includes(specialty)) {
                textarea.value = currentValue + ', ' + specialty;
            } else if (!currentValue) {
                textarea.value = specialty;
            }
            
            textarea.focus();
        }

        // Star rating functionality
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        const ratingText = document.getElementById('ratingText');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                ratingInput.value = rating;
                
                // Update star display
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
                
                // Update rating text
                const ratingTexts = ['', 'Beginner', 'Experienced', 'Professional', 'Expert', 'Master Technician'];
                ratingText.textContent = ratingTexts[rating] || 'No rating';
            });
        });

        // Get current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                document.querySelector('.location-btn').textContent = '📍 Getting location...';
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                    document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                    document.querySelector('.location-btn').textContent = '✅ Location captured!';
                    
                    setTimeout(() => {
                        document.querySelector('.location-btn').textContent = '📍 Get Workshop Location';
                    }, 3000);
                }, function(error) {
                    alert('Unable to retrieve your location. Please enter manually.');
                    document.querySelector('.location-btn').textContent = '📍 Get Workshop Location';
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        // Initialize rating on page load
        window.addEventListener('load', function() {
            const savedRating = parseInt(document.getElementById('rating').value);
            if (savedRating > 0) {
                stars.forEach((s, index) => {
                    if (index < savedRating) {
                        s.classList.add('active');
                    }
                });
                const ratingTexts = ['', 'Beginner', 'Experienced', 'Professional', 'Expert', 'Master Technician'];
                ratingText.textContent = ratingTexts[savedRating] || 'No rating';
            }
        });
    </script>
</body>
</html>