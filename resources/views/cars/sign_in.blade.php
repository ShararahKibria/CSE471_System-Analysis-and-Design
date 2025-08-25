<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .signin-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
            border: 1px solid #e9ecef;
        }

        .header {
            background: #000;
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 2em;
            margin-bottom: 8px;
            font-weight: 300;
        }

        .header p {
            font-size: 0.95em;
            opacity: 0.8;
            font-weight: 300;
        }

        .form-section {
            padding: 40px 30px;
            background: white;
        }

        .role-selection {
            margin-bottom: 30px;
        }

        .role-selection label {
            display: block;
            font-weight: 500;
            color: #333;
            margin-bottom: 15px;
            font-size: 0.95em;
        }

        .role-options {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            z-index: 10;
        }

        .role-card {
            display: flex;
            align-items: center;
            padding: 15px 18px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #fff;
        }

        .role-card:hover {
            border-color: #333;
            background: #f8f9fa;
        }

        .role-option input[type="radio"]:checked + .role-card,
        .role-card.selected {
            border-color: #000;
            background: #000;
            color: white;
        }

        .role-icon {
            font-size: 1.4em;
            margin-right: 12px;
            width: 40px;
            text-align: center;
        }

        .role-info h3 {
            font-size: 1em;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.9em;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s ease;
            background: #fff;
        }

        .form-group input:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 1em;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            cursor: pointer;
            font-size: 1em;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #000;
        }

        .submit-btn {
            background: #000;
            color: white;
            border: none;
            padding: 16px 30px;
            font-size: 1em;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
        }

        .submit-btn:hover {
            background: #333;
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.8em;
            margin-top: 6px;
            display: none;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .loading i {
            font-size: 1.2em;
            margin-bottom: 8px;
        }

        @media (max-width: 480px) {
            .signin-container {
                margin: 10px;
                max-width: 100%;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 1.8em;
            }
        }

        .form-group input.error {
            border-color: #dc3545;
        }

        .form-group input.success {
            border-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="signin-container">
        <div class="header">
            <h1><i class="fas fa-sign-in-alt"></i> Sign In</h1>
            <p>Enter your credentials to access your account</p>
        </div>

        <div class="form-section">
            <form action="/sign_in" method="POST" id="signinForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="role-selection">
                    <label>Select Your Role</label>
                    <div class="role-options">
                        <div class="role-option">
                            <input type="radio" id="mechanic" name="role" value="mechanic" required>
                            <div class="role-card">
                                <div class="role-icon">
                                    <i class="fas fa-wrench"></i>
                                </div>
                                <div class="role-info">
                                    <h3>Mechanic</h3>
                                </div>
                            </div>
                        </div>

                        <div class="role-option">
                            <input type="radio" id="car_owner" name="role" value="car_owner" required>
                            <div class="role-card">
                                <div class="role-icon">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="role-info">
                                    <h3>Car Owner</h3>
                                </div>
                            </div>
                        </div>

                        <div class="role-option">
                            <input type="radio" id="driver" name="role" value="driver" required>
                            <div class="role-card">
                                <div class="role-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="role-info">
                                    <h3>Driver</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="error-message" id="roleError">Please select your role</div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" required placeholder="Enter your email">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    <div class="error-message" id="emailError">Please enter a valid email address</div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" required placeholder="Enter your password">
                        <i class="fas fa-lock input-icon"></i>
                        <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
                    </div>
                    <div class="error-message" id="passwordError">Password must be at least 6 characters</div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="register-section">
                <p>Don't have an account?</p>
                <a href="/register/register" class="register-btn">
                    <i class="fas fa-user-plus"></i> Create Account
                </a>
            </div>

            <div class="loading" id="loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Signing you in...</p>
            </div>
        </div>
    </div>

    <script>
        // Role selection handling
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.role-card').forEach(card => {
                    card.classList.remove('selected');
                });
                
                // Add selected class to current card
                if (this.checked) {
                    this.nextElementSibling.classList.add('selected');
                }
            });
        });

        // Make role cards clickable
        document.querySelectorAll('.role-card').forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.previousElementSibling;
                radio.checked = true;
                
                // Trigger change event
                radio.dispatchEvent(new Event('change'));
            });
        });

        // Password toggle functionality
        document.getElementById('passwordToggle').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Form validation
        document.getElementById('signinForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Hide all error messages
            document.querySelectorAll('.error-message').forEach(msg => {
                msg.style.display = 'none';
            });

            // Remove error classes
            document.querySelectorAll('.form-group input').forEach(input => {
                input.classList.remove('error', 'success');
            });

            // Validate role selection
            const roleSelected = document.querySelector('input[name="role"]:checked');
            if (!roleSelected) {
                document.getElementById('roleError').style.display = 'block';
                isValid = false;
            }

            // Validate email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                document.getElementById('emailError').style.display = 'block';
                document.getElementById('email').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('email').classList.add('success');
            }

            // Validate password
            const password = document.getElementById('password').value;
            if (!password || password.length < 6) {
                document.getElementById('passwordError').style.display = 'block';
                document.getElementById('password').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('password').classList.add('success');
            }

            if (!isValid) {
                e.preventDefault();
            } else {
                // Show loading
                document.getElementById('loading').style.display = 'block';
                this.style.display = 'none';
            }
        });

        // Input focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.remove('error');
            });
        });
    </script>
</body>
</html>