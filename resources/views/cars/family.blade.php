<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Cars - Premium Rental Experience</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            transition: all 0.5s ease;
            position: relative;
        }

        /* Dynamic Backgrounds */
        .bg-gradient-sunset {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-gradient-ocean {
            background: linear-gradient(135deg, #667db6 0%, #0082c8 100%);
        }
        .bg-gradient-forest {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 50%, #6c5ce7 100%);
        }
        .bg-gradient-luxury {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%);
        }
        .bg-gradient-warm {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        /* Animated Background Elements */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 10%;
            left: 50%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }

        /* Header Section */
        .header {
            text-align: center;
            padding: 60px 20px;
            color: white;
            position: relative;
            z-index: 10;
        }

        .header h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            animation: slideInDown 1s ease-out;
        }

        .tagline {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: slideInUp 1s ease-out 0.2s both;
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 30px;
            animation: slideInUp 1s ease-out 0.4s both;
        }

        .feature {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            min-width: 200px;
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.2);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: block;
        }

        .feature h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        /* Background Selector */
        .bg-selector {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .bg-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
        }

        .bg-option:hover {
            transform: scale(1.2);
            border-color: white;
        }

        .bg-option.active {
            border-color: white;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        /* Car Cards Container */
        .main-content {
            padding: 40px 20px;
            position: relative;
            z-index: 10;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: all 0.4s ease;
            transform: translateY(50px);
            opacity: 0;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .card.animate {
            transform: translateY(0);
            opacity: 1;
        }

        .card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }

        .card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.4s ease;
        }

        .card:hover img {
            transform: scale(1.05);
        }

        .card-content {
            padding: 25px;
        }

        .card-content h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            font-size: 1.4rem;
            font-weight: bold;
        }

        .card-content p {
            margin: 8px 0;
            color: #555;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .card-content p strong {
            color: #2c3e50;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .button:hover::before {
            left: 100%;
        }

        .self-service {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .self-service:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .with-driver {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .with-driver:hover {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
        }

        /* Fullscreen image */
        .popup {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .popup img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            animation: popupZoom 0.3s ease-out;
        }

        @keyframes popupZoom {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Loading Animation */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            color: white;
            font-size: 1.2rem;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Animations */
        @keyframes slideInDown {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        @keyframes slideInUp {
            0% { transform: translateY(50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        /* Cart & Checkout Styles */
        .checkout-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            border: none;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(231, 76, 60, 0.3);
            z-index: 1000;
            transition: all 0.3s ease;
            display: none;
        }

        .checkout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(231, 76, 60, 0.4);
        }

        .checkout-btn.show {
            display: block;
            animation: bounceIn 0.5s ease-out;
        }

        .cart-count {
            background: white;
            color: #e74c3c;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            margin-left: 10px;
        }

        /* Checkout Page Styles */
        .checkout-page {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 9999;
            overflow-y: auto;
        }

        .checkout-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            position: relative;
            animation: slideInUp 0.5s ease-out;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 25px;
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #7f8c8d;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: #e74c3c;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }

        .checkout-header h2 {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .order-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .order-summary h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .order-item:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.1rem;
            color: #2c3e50;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .item-service {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .item-price {
            font-weight: bold;
            color: #27ae60;
            font-size: 1.1rem;
        }

        .payment-section {
            margin-top: 30px;
        }

        .payment-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .payment-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .payment-option {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .payment-option:hover {
            border-color: #3498db;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
        }

        .payment-option.selected {
            border-color: #27ae60;
            background: #d5f4e6;
        }

        .payment-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: block;
        }

        .payment-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .payment-name {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .confirm-btn {
            width: 100%;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .confirm-btn:hover {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }

        .confirm-btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3) translateY(50px); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h1 { font-size: 2.5rem; }
            .tagline { font-size: 1.1rem; }
            .features { gap: 20px; }
            .feature { min-width: 150px; padding: 15px; }
            .card-container { grid-template-columns: 1fr; }
            .bg-selector { top: 10px; right: 10px; padding: 10px; }
            .checkout-container { margin: 20px; padding: 25px; }
            .checkout-btn { bottom: 15px; right: 15px; padding: 12px 20px; }
            .payment-options { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="bg-gradient-sunset">
    <!-- Animated Background Shapes -->
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Background Selector -->
    <div class="bg-selector">
        <div class="bg-option active" data-bg="bg-gradient-sunset" style="background: linear-gradient(135deg, #667eea, #764ba2);"></div>
        <div class="bg-option" data-bg="bg-gradient-ocean" style="background: linear-gradient(135deg, #667db6, #0082c8);"></div>
        <div class="bg-option" data-bg="bg-gradient-forest" style="background: linear-gradient(135deg, #74b9ff, #6c5ce7);"></div>
        <div class="bg-option" data-bg="bg-gradient-luxury" style="background: linear-gradient(135deg, #2c3e50, #34495e);"></div>
        <div class="bg-option" data-bg="bg-gradient-warm" style="background: linear-gradient(135deg, #fa709a, #fee140);"></div>
    </div>

    <!-- Header Section -->
    <div class="header">
        <h1>🚗 FAMILY Cars</h1>
        <p class="tagline">Experience the Freedom of Premium Family Travel</p>
        
        <div class="features">
            <div class="feature">
                <span class="feature-icon">🛡️</span>
                <h3>Safe & Reliable</h3>
                <p>All vehicles undergo rigorous safety inspections and maintenance</p>
            </div>
            <div class="feature">
                <span class="feature-icon">💰</span>
                <h3>Best Prices</h3>
                <p>Competitive rates with no hidden fees or surprise charges</p>
            </div>
            <div class="feature">
                <span class="feature-icon">🎯</span>
                <h3>Perfect for Families</h3>
                <p>Spacious vehicles designed for comfort and convenience</p>
            </div>
            <div class="feature">
                <span class="feature-icon">⚡</span>
                <h3>Instant Booking</h3>
                <p>Quick and easy reservation process, available 24/7</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="card-container" id="carList">
            <div class="loading">
                <div class="spinner"></div>
                Loading amazing cars for you...
            </div>
        </div>
    </div>

    <!-- Checkout Button -->
    <button class="checkout-btn" id="checkoutBtn" onclick="openCheckout()">
        🛒 Checkout <span class="cart-count" id="cartCount">0</span>
    </button>

    <!-- Checkout Page -->
    <div class="checkout-page" id="checkoutPage">
        <div class="checkout-container">
            <button class="close-btn" onclick="closeCheckout()">&times;</button>
            
            <div class="checkout-header">
                <h2>🛒 Checkout</h2>
                <p>Review your car rental selections</p>
            </div>

            <div class="order-summary">
                <h3>📋 Order Summary</h3>
                <div id="orderItems"></div>
            </div>

            <div class="payment-section">
                <h3>💳 Payment Method</h3>
                <div class="payment-options">
                    <div class="payment-option" data-payment="cash">
                        <span class="payment-icon">💵</span>
                        <div class="payment-name">Cash Payment</div>
                    </div>
                    <div class="payment-option" data-payment="bkash">
                        <img src="/images/bkash.png" alt="bKash" class="payment-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span class="payment-icon" style="display:none;">📱</span>
                        <div class="payment-name">bKash</div>
                    </div>
                    <div class="payment-option" data-payment="nagad">
                        <img src="/images/nagad.JPG" alt="Nagad" class="payment-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span class="payment-icon" style="display:none;">💳</span>
                        <div class="payment-name">Nagad</div>
                    </div>
                </div>
                
                <button class="confirm-btn" id="confirmBtn" onclick="confirmOrder()" disabled>
                    Confirm Order
                </button>
            </div>
        </div>
    </div>

    <!-- Fullscreen image viewer -->
    <div class="popup" id="imagePopup" onclick="this.style.display='none'">
        <img id="popupImage" src="" alt="">
    </div>

    <script>
        // Background selector functionality
        document.querySelectorAll('.bg-option').forEach(option => {
            option.addEventListener('click', () => {
                // Remove active class from all options
                document.querySelectorAll('.bg-option').forEach(opt => opt.classList.remove('active'));
                // Add active class to clicked option
                option.classList.add('active');
                
                // Change body background
                const bgClass = option.getAttribute('data-bg');
                document.body.className = bgClass;
            });
        });

        // Cart functionality
        let cart = [];

        function addToCart(carIndex, carName, pricePerDay, serviceType) {
            const price = serviceType === 'with-driver' ? pricePerDay + 5 : pricePerDay;
            
            const item = {
                id: Date.now() + carIndex, // Unique ID
                carName: carName,
                serviceType: serviceType,
                pricePerDay: pricePerDay,
                totalPrice: price
            };
            
            cart.push(item);
            updateCartDisplay();
            
            // Show success animation
            showAddToCartSuccess();
        }

        function updateCartDisplay() {
            const cartCount = document.getElementById('cartCount');
            const checkoutBtn = document.getElementById('checkoutBtn');
            
            cartCount.textContent = cart.length;
            
            if (cart.length > 0) {
                checkoutBtn.classList.add('show');
            } else {
                checkoutBtn.classList.remove('show');
            }
        }

        function showAddToCartSuccess() {
            const checkoutBtn = document.getElementById('checkoutBtn');
            checkoutBtn.style.transform = 'scale(1.1)';
            checkoutBtn.style.background = 'linear-gradient(135deg, #27ae60, #2ecc71)';
            
            setTimeout(() => {
                checkoutBtn.style.transform = 'scale(1)';
                checkoutBtn.style.background = 'linear-gradient(135deg, #e74c3c, #c0392b)';
            }, 300);
        }

        function openCheckout() {
            if (cart.length === 0) return;
            
            document.getElementById('checkoutPage').style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            updateOrderSummary();
        }

        function closeCheckout() {
            document.getElementById('checkoutPage').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function updateOrderSummary() {
            const orderItems = document.getElementById('orderItems');
            let html = '';
            let total = 0;
            
            cart.forEach((item, index) => {
                const serviceText = item.serviceType === 'with-driver' ? 'With Driver (+$5)' : 'Self-Service';
                html += `
                    <div class="order-item">
                        <div class="item-details">
                            <div class="item-name">${item.carName}</div>
                            <div class="item-service">${serviceText}</div>
                        </div>
                        <div class="item-price">${item.totalPrice}/day</div>
                    </div>
                `;
                total += item.totalPrice;
            });
            
            html += `
                <div class="order-item">
                    <div class="item-details">
                        <div class="item-name">Total Amount</div>
                    </div>
                    <div class="item-price">${total}/day</div>
                </div>
            `;
            
            orderItems.innerHTML = html;
        }

        // Payment method selection
        document.addEventListener('DOMContentLoaded', () => {
            const paymentOptions = document.querySelectorAll('.payment-option');
            const confirmBtn = document.getElementById('confirmBtn');
            
            paymentOptions.forEach(option => {
                option.addEventListener('click', () => {
                    paymentOptions.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');
                    confirmBtn.disabled = false;
                });
            });
        });

        function confirmOrder() {
            const selectedPayment = document.querySelector('.payment-option.selected');
            if (!selectedPayment) return;
            
            const paymentMethod = selectedPayment.getAttribute('data-payment');
            
            // Show success message
            alert(`🎉 Order Confirmed!\n\nPayment Method: ${paymentMethod.toUpperCase()}\nTotal Cars: ${cart.length}\nThank you for choosing Family Cars!`);
            
            // Clear cart
            cart = [];
            updateCartDisplay();
            closeCheckout();
        }

        // Card animation observer
        function animateCards() {
            const cards = document.querySelectorAll('.card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('animate');
                        }, index * 150); // Stagger animation
                    }
                });
            }, {
                threshold: 0.1
            });

            cards.forEach(card => observer.observe(card));
        }

        async function loadCars() {
            try {
                // Fetch data from your actual API endpoint
                const response = await fetch('http://127.0.0.1:8000/test-categories2');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const cars = await response.json();

                const container = document.getElementById('carList');
                container.innerHTML = '';

                // Process all cars from the API response
                cars.forEach((car, index) => {
                    const card = document.createElement('div');
                    card.className = 'card';

                    // Fix Google Drive image URLs
                    let imageUrl = car.image_url;
                    if (imageUrl.includes('drive.google.com')) {
                        const fileId = imageUrl.match(/id=([^&]+)/)[1];
                        imageUrl = `https://drive.google.com/thumbnail?id=${fileId}`;
                    }

                    card.innerHTML = `
                        <img src="${imageUrl}" alt="${car.name}" onerror="this.src='https://via.placeholder.com/300x220/667eea/ffffff?text=🚗+Car+Image'" onclick="showImage('${imageUrl}')">
                        <div class="card-content">
                            <h3>${car.name}</h3>
                            <p><strong>👥 Seats:</strong> ${car.seat_capacity} passengers</p>
                            <p><strong>💵 Price/Day:</strong> $${car.price_per_day}</p>
                            <p><strong>📝 Description:</strong> ${car.description}</p>
                            <div class="button-group">
                                <button class="button self-service" onclick="addToCart(${index}, '${car.name}', ${car.price_per_day}, 'self-service')">🔑 Self-Service</button>
                                <button class="button with-driver" onclick="addToCart(${index}, '${car.name}', ${car.price_per_day}, 'with-driver')">👨‍✈️ With Driver</button>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                });

                // Animate cards after they're added to DOM
                setTimeout(() => {
                    animateCards();
                }, 100);

            } catch (err) {
                console.error('Error loading cars:', err);
                document.getElementById('carList').innerHTML = `
                    <div style="text-align: center; width: 100%; color: white; background: rgba(255,255,255,0.1); padding: 40px; border-radius: 15px; backdrop-filter: blur(10px);">
                        <h3>⚠️ Unable to Load Cars</h3>
                        <p>Please check your connection and try again.</p>
                        <button onclick="loadCars()" style="margin-top: 15px; padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer;">🔄 Retry</button>
                    </div>
                `;
            }
        }

        function showImage(url) {
            document.getElementById('popupImage').src = url;
            document.getElementById('imagePopup').style.display = 'flex';
        }

        // Add smooth scrolling
        document.documentElement.style.scrollBehavior = 'smooth';

        // Load cars when page loads
        loadCars();

        // Add some extra interactivity
        document.addEventListener('mousemove', (e) => {
            const shapes = document.querySelectorAll('.shape');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 0.5;
                const x = mouseX * speed;
                const y = mouseY * speed;
                shape.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    </script>
</body>
</html>