<?php
// db.php - Database connection
$host = 'localhost';
$db   = 'car_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $car_make = $_POST['car_make'];
    $car_model = $_POST['car_model'];
    $car_year = $_POST['car_year'];
    $car_color = $_POST['car_color'];
    $license_plate = $_POST['license_plate'];
    $vin = $_POST['vin'];
    $car_condition = $_POST['car_condition'];
    $mileage = $_POST['mileage'];
    $location = $_POST['location'];

    $stmt = $pdo->prepare("
        INSERT INTO car_owners 
        (full_name, phone, email, car_make, car_model, car_year, car_color, license_plate, vin, car_condition, mileage, location)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$full_name, $phone, $email, $car_make, $car_model, $car_year, $car_color, $license_plate, $vin, $car_condition, $mileage, $location]);

    $success = "Car Owner Registered Successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your Vehicle - RACECAR</title>
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

        .floating-car {
            position: absolute;
            font-size: 40px;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        .floating-car:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-car:nth-child(2) {
            top: 30%;
            right: 20%;
            animation-delay: 5s;
        }

        .floating-car:nth-child(3) {
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

        .success-message {
            background: linear-gradient(135deg, #00b894, #00a085);
            color: white;
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
        .form-group select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid rgba(52, 73, 94, 0.1);
            border-radius: 12px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            transform: translateY(-2px);
            background: white;
        }

        .form-group input:valid {
            border-color: #00b894;
        }

        .floating-label {
            position: absolute;
            left: 20px;
            top: 15px;
            color: #7f8c8d;
            font-size: 16px;
            pointer-events: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            padding: 0 5px;
        }

        .form-group input:focus + .floating-label,
        .form-group input:not(:placeholder-shown) + .floating-label {
            top: -10px;
            left: 15px;
            font-size: 12px;
            color: #ff6b35;
            font-weight: 600;
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

        .car-makes {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .car-make-chip {
            background: rgba(255, 107, 53, 0.1);
            color: #ff6b35;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            border: 1px solid rgba(255, 107, 53, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .car-make-chip:hover {
            background: #ff6b35;
            color: white;
            transform: translateY(-2px);
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

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 107, 53, 0.3);
            border-left: 4px solid #ff6b35;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        .form-group input:focus ~ .input-icon {
            color: #ff6b35;
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-car">🚗</div>
        <div class="floating-car">🏎️</div>
        <div class="floating-car">🚙</div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Register Your Vehicle</h1>
            <p>Join the RACECAR family - Register your car with premium service</p>
        </div>

        <div class="form-container">
            <?php if($success): ?>
                <div class="success-message"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST" id="registrationForm">
                @csrf
                <div class="form-grid">
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">👤</span>
                            Personal Information
                        </h3>

                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <div class="input-container">
                                <input type="text" id="full_name" name="full_name" placeholder=" " required>
                                <div class="input-icon">👤</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="input-container">
                                <input type="tel" id="phone" name="phone" placeholder=" " required>
                                <div class="input-icon">📱</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-container">
                                <input type="email" id="email" name="email" placeholder=" " required>
                                <div class="input-icon">📧</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="location">Location</label>
                            <div class="input-container">
                                <input type="text" id="location" name="location" placeholder=" ">
                                <div class="input-icon">📍</div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">🚗</span>
                            Vehicle Information
                        </h3>

                        <div class="form-group">
                            <label for="car_make">Car Make</label>
                            <div class="input-container">
                                <input type="text" id="car_make" name="car_make" placeholder=" " required>
                                <div class="input-icon">🏭</div>
                            </div>
                            <div class="car-makes">
                                <span class="car-make-chip" onclick="fillMake('Toyota')">Toyota</span>
                                <span class="car-make-chip" onclick="fillMake('Honda')">Honda</span>
                                <span class="car-make-chip" onclick="fillMake('BMW')">BMW</span>
                                <span class="car-make-chip" onclick="fillMake('Mercedes')">Mercedes</span>
                                <span class="car-make-chip" onclick="fillMake('Audi')">Audi</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="car_model">Car Model</label>
                            <div class="input-container">
                                <input type="text" id="car_model" name="car_model" placeholder=" " required>
                                <div class="input-icon">🚙</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="car_year">Year</label>
                            <div class="input-container">
                                <input type="number" id="car_year" name="car_year" min="1990" max="<?= date('Y') ?>" placeholder=" " required>
                                <div class="input-icon">📅</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="car_color">Color</label>
                            <div class="input-container">
                                <input type="text" id="car_color" name="car_color" placeholder=" ">
                                <div class="input-icon">🎨</div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Details Section -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">📋</span>
                            Vehicle Details
                        </h3>

                        <div class="form-group">
                            <label for="license_plate">License Plate</label>
                            <div class="input-container">
                                <input type="text" id="license_plate" name="license_plate" placeholder=" " required>
                                <div class="input-icon">🔖</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="vin">VIN Number</label>
                            <div class="input-container">
                                <input type="text" id="vin" name="vin" placeholder=" ">
                                <div class="input-icon">🔢</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="car_condition">Condition</label>
                            <select id="car_condition" name="car_condition">
                                <option value="Excellent">Excellent ⭐⭐⭐⭐⭐</option>
                                <option value="Good" selected>Good ⭐⭐⭐⭐</option>
                                <option value="Fair">Fair ⭐⭐⭐</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mileage">Mileage</label>
                            <div class="input-container">
                                <input type="number" id="mileage" name="mileage" placeholder=" ">
                                <div class="input-icon">⛽</div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="submit-section">
                        <button type="submit" class="submit-btn">
                            Register My Vehicle
                        </button>
                        <p style="margin-top: 15px; color: #7f8c8d; font-size: 0.9em;">
                            🔒 Your information is secure and encrypted
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fill car make function
        function fillMake(make) {
            document.getElementById('car_make').value = make;
            document.getElementById('car_make').focus();
        }

        // Form submission with loading
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            document.getElementById('loadingOverlay').style.display = 'flex';
        });

        // Add smooth scroll and focus effects
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.style.transform = 'translateY(-2px)';
                this.parentElement.parentElement.style.transition = 'all 0.3s ease';
            });

            input.addEventListener('blur', function() {
                this.parentElement.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Auto-capitalize names
        document.getElementById('full_name').addEventListener('input', function(e) {
            this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
        });

        // Auto-format phone number
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 6) {
                value = value.substring(0,3) + '-' + value.substring(3,6) + '-' + value.substring(6,10);
            } else if (value.length >= 3) {
                value = value.substring(0,3) + '-' + value.substring(3,6);
            }
            this.value = value;
        });

        // Auto-uppercase license plate
        document.getElementById('license_plate').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });

        // Add entrance animations for form sections
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'slideUp 0.6s ease-out';
                }
            });
        });

        document.querySelectorAll('.form-section').forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>