<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Welcome</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: white;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .button-container {
            display: flex;
            gap: 20px;
        }

        .navbar {
            position: absolute;
            top: 0;
            max-height: 80px;
            left: 5%;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.5);
            color: black;
            padding: 10px 20px;
            z-index: 3;
            animation: slideFromTop 1s ease forwards;
        }
        .logo{
            position: relative;
            top: 30%;
            left: 10%;
            min-width: 12%;
        }

        .nav-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .nav-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .main {
            margin: 10px auto 0;
            position: relative;
            width: 95%;
            height: 95%;
            gap: 20px;
        }

        .content-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            top: 12%;
            margin: 0 auto;
        }

        .video-container {
            position: absolute;
            width: 100%;
            height: 100%;
            clip-path: polygon(
                0% 60%, 5% 50%, 45% 50%, 50% 40%, 50% 5%,
                55% 0%, 100% 0%, 100% 78%, 85% 78%, 83% 82%,
                83% 86%, 85% 90%, 100% 90%, 100% 100%,
                0% 100%, 0% 60%
            );
            z-index: 1;
            animation: slideFromRight 1.2s ease forwards;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            display: block;
        }

        .video-text {
            position: absolute;
            top: 5%;
            left: 5%;
            min-width: 40%;
            max-width: 400px;
            color: #2c3e50;
            font-size: 14px;
            line-height: 1.5;
            z-index: 2;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(-50%);
            animation: slideFromLeft 1.2s ease forwards;
        }

        .video-text h2 {
            margin: 0 0 12px 0;
            font-size: 20px;
            font-weight: 600;
            color: #34495e;
        }

        .video-text ul {
            margin: 12px 0;
            padding-left: 18px;
        }

        .video-text li {
            margin: 6px 0;
            color: #5d6d7e;
            font-size: 13px;
        }

        .cta-button, .cta-button2 {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cta-button2 {
            position: absolute;
            top: 79%;
            left: 85%;
            min-width: 12%;
            animation: slideFromBottom 1.8s ease forwards;
        }

        .cta-button:hover, .cta-button2:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
        }

        @keyframes slideFromTop { from { transform: translateY(-100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes slideFromLeft { from { transform: translateX(-100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideFromRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideFromBottom { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* MODAL STYLES */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: rgba(20, 20, 20, 0.85);
            color: white;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            position: relative;
            animation: fadeInScale 0.4s ease;
        }

        @keyframes fadeInScale {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
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
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .category-option:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 768px) {
            .video-text { width: 45%; font-size: 12px; padding: 15px; }
            .video-text h2 { font-size: 16px; }
            .video-text li { font-size: 11px; }
            .cta-button { font-size: 12px; padding: 8px 16px; }
        }
    </style>
</head>
<body>
    <div class='main'>
        <div class='navbar'>
            <div class='logo'>
                <h1 style="margin: 0; font-size: 48px; font-weight: 700;">Rentor</h1>
                <p style="margin: 0; font-size: 14px; color: #666; font-style: italic;">Your Car, Your Way</p>
            </div>
            <div class='button-container'>
                <button class='nav-button'>Home</button>
                <button class='nav-button'><a href="/admin/admin">Admin</a></button>
                <button class='nav-button'>About Me</button>
                <button class='nav-button'>Channels</button>
                <button class='nav-button'>Contact</button>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="video-container">
                <video autoplay muted loop>
                    <source src="/videos/video1.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <div class="video-text">
                <h1>Drive, Rent, or Service – All in One App</h1>
                <p>Whether you own a car, drive one, or service them — Rentor is your all-in-one platform to connect, earn, and enjoy premium automotive solutions.</p>
                
                <h2>Own a Car? <span class="highlight">Make It Work for You</span></h2>
                <ul>
                    <li>Find trusted drivers anywhere in the country</li>
                    <li>Choose between <strong>Premium Home Service</strong> or <strong>Standard Workshop Service</strong></li>
                    <li>Register your car for exclusive premium services</li>
                </ul>

                <button class='cta-button'><a href="/cars/sign_in" >Start Your Journey</a></button>
            </div>
            <button class="cta-button2">Services you might want to explore</button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="discoverModal">
        <div class="modal-content">
            <button class="modal-close" id="modalClose">&times;</button>
            <h2 class="modal-title">Choose Your Style</h2>
            <p class="modal-subtitle">Select the perfect category for your needs</p>
            
            <div class="category-options">
                <a href="/cars/sign_in" class="category-option">
                    <div class="category-info">
                        <div class="category-name">Driver</div>
                        <div class="category-desc">Find trusted drivers for your family</div>
                    </div>
                    <div class="category-icon">👨‍👩‍👧‍👦</div>
                </a>

                <a href="/cars/sign_in" class="category-option">
                    <div class="category-info">
                        <div class="category-name">Find a mechanic around you</div>
                        <div class="category-desc">Get connected with trusted mechanics in your area</div>
                    </div>
                    <div class="category-icon">🔧</div>
                </a>

                <a href="/cars/sign_in" class="category-option">
                    <div class="category-info">
                        <div class="category-name">Find Premium Service</div>
                        <div class="category-desc">Get the best premium services for your vehicle at your doorstep</div>
                    </div>
                    <div class="category-icon">🚗</div>
                </a>
            </div>
        </div>
    </div>

    <script>
        const openModalBtn = document.querySelector('.cta-button2');
        const modal = document.getElementById('discoverModal');
        const closeModalBtn = document.getElementById('modalClose');

        openModalBtn.addEventListener('click', () => {
            modal.classList.add('active');
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.remove('active');
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    </script>
</body>
</html>
