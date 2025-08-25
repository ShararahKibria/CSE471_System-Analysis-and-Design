<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Role</title>
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
            padding: 40px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        .header h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 1.2rem;
        }

        .roles-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            justify-items: center;
        }

        .role-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            max-width: 350px;
            width: 100%;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }

        .role-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .role-card:hover .role-image {
            border-color: #764ba2;
            transform: scale(1.05);
        }

        .role-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .role-description {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .register-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        /* Placeholder image styling */
        .placeholder-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 4px solid #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .role-card:hover .placeholder-image {
            border-color: #764ba2;
            color: #764ba2;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .header p {
                font-size: 1rem;
            }
            
            .roles-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .role-card {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Choose Your Role</h1>
            <p>Select how you'd like to join our platform</p>
        </div>

        <div class="roles-container">
            <!-- Driver Card -->
            <div class="role-card">
                <div class="placeholder-image">🚗</div>
                <img src="{{ asset('images/image4.JPEG') }}" alt="Driver" class="role-image">
                <h2 class="role-title">Driver</h2>
                <p class="role-description">
                    Join as a professional driver and start earning by providing reliable transportation services to customers in your area.
                </p>
                <button class="register-btn" onclick="registerAsDriver()">
                    Register as Driver
                </button>
            </div>

            <!-- Mechanic Card -->
            <div class="role-card">
                <div class="placeholder-image">🔧</div>
                <img src="{{ asset('images/image3.JPEG') }}" alt="Mechanic" class="role-image">
                <h2 class="role-title">Mechanic</h2>
                <p class="role-description">
                    Register as a certified mechanic and offer your automotive repair and maintenance services to vehicle owners.
                </p>
                <button class="register-btn" onclick="registerAsMechanic()">
                    Register as Mechanic
                </button>
            </div>

            <!-- Car Owner Card -->
            <div class="role-card">
                <div class="placeholder-image">👤</div>
                <img src="{{ asset('images/image1.jpg') }}" alt="Car Owner" class="role-image">
                <h2 class="role-title">Car Owner</h2>
                <p class="role-description">
                    Register your vehicle and connect with trusted drivers and mechanics for all your automotive needs.
                </p>
                <button class="register-btn" onclick="registerYourCar()">
                    Register Your Car
                </button>
            </div>
        </div>
    </div>

    <script>
        function registerAsDriver() {
            console.log('Register as Driver clicked');
            // Add your Laravel route/action here
            window.location.href = '/cars/add_driver';
        }

        function registerAsMechanic() {
            console.log('Register as Mechanic clicked');
            // Add your Laravel route/action here
            window.location.href = '/cars/add_mechanic';
        }

        function registerYourCar() {
            console.log('Register Your Car clicked');
            // Add your Laravel route/action here
            window.location.href = '/cars/add_car';
        }
    </script>
</body>
</html>