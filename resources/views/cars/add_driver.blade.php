<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register as Driver - RACECAR</title>
<style>
/* Modern CSS with improved design */
* { margin:0; padding:0; box-sizing:border-box; }
body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    background: linear-gradient(135deg,#667eea,#764ba2); 
    min-height:100vh; 
    padding:20px; 
}
.container { 
    max-width: 900px; 
    margin:auto; 
    background: rgba(255,255,255,0.95); 
    backdrop-filter: blur(20px); 
    border-radius:25px; 
    padding:40px; 
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
.header { text-align:center; margin-bottom:30px; }
.header h1 { color:#333; font-size:2.5em; font-weight:800; margin-bottom:10px; }
.header p { color:#555; font-size:1.1em; }

/* Alert Messages */
.alert { 
    padding:15px 20px; 
    margin-bottom:20px; 
    border-radius:10px; 
    font-weight:600;
    border-left: 4px solid;
}
.alert-success { 
    background: rgba(16, 185, 129, 0.1); 
    color: #047857; 
    border-color: #10b981;
}
.alert-error { 
    background: rgba(239, 68, 68, 0.1); 
    color: #dc2626; 
    border-color: #ef4444;
}
.alert ul { margin:0; padding-left:20px; }

.form-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(300px,1fr)); gap:30px; }
.form-section { 
    background: rgba(255,255,255,0.7); 
    padding:25px; 
    border-radius:20px; 
    border:1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.form-section:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
.section-title { 
    font-size:1.3em; 
    font-weight:700; 
    margin-bottom:20px; 
    color:#2d3748;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 10px;
}
.form-group { margin-bottom:20px; position: relative; }
.form-group label { 
    display:block; 
    margin-bottom:8px; 
    font-weight:600; 
    color:#34495e; 
}
.form-group input, .form-group select { 
    width:100%; 
    padding:12px 15px; 
    border-radius:10px; 
    border:2px solid #e2e8f0; 
    font-size:16px;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
}
.form-group input:focus, .form-group select:focus { 
    outline:none; 
    border-color:#667eea; 
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
.form-group.error input, .form-group.error select {
    border-color: #ef4444;
}
.error-text {
    color: #ef4444;
    font-size: 0.875em;
    margin-top: 5px;
    font-weight: 500;
}

.submit-section { grid-column: 1 / -1; text-align:center; margin-top:20px; }
.submit-btn { 
    background: linear-gradient(135deg,#ff6b35,#f7931e); 
    color:white; 
    padding:15px 40px; 
    border:none; 
    border-radius:50px; 
    font-size:1.1em; 
    font-weight:700; 
    cursor:pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}
.submit-btn:hover { 
    opacity:0.9; 
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
}
.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.rating-container { display:flex; align-items:center; gap:10px; }
.star { 
    font-size:24px; 
    color:#ddd; 
    cursor:pointer; 
    transition:all 0.3s ease;
}
.star:hover { color:#ffd700; transform:scale(1.2); }
.star.active { color:#ffd700; transform:scale(1.1); }

.location-btn { 
    margin-top:10px; 
    padding:10px 15px; 
    border-radius:8px; 
    border:none; 
    background: rgba(255,107,53,0.2); 
    cursor:pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}
.location-btn:hover { 
    background:#ff6b35; 
    color:white; 
    transform: translateY(-1px);
}
.location-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #ff6b35;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media(max-width:768px){ 
    .form-grid{ grid-template-columns:1fr; }
    .container { padding: 20px; margin: 10px; }
    .header h1 { font-size: 2em; }
}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Register as Driver</h1>
        <p>Join our elite team of professional drivers - Start earning today</p>
    </div>

    <!-- Success Message -->
    <div id="successAlert" class="alert alert-success" style="display:none;">
        <strong>Success!</strong> Driver registered successfully!
    </div>

    <!-- Error Messages -->
    <div id="errorAlert" class="alert alert-error" style="display:none;">
        <strong>Error!</strong> <span id="errorMessage">Please fix the errors below.</span>
    </div>

    <form id="registrationForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrfToken">
        <div class="form-grid">

            <!-- Personal Info -->
            <div class="form-section">
                <div class="section-title">Personal Information</div>
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    <div class="error-text" id="name-error"></div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" placeholder="Your phone number" required>
                    <div class="error-text" id="phone-error"></div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" placeholder="Your email address" required>
                    <div class="error-text" id="email-error"></div>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="Create a secure password" required minlength="6">
                    <div class="error-text" id="password-error"></div>
                </div>
            </div>

            <!-- License & Professional Details -->
            <div class="form-section">
                <div class="section-title">License & Professional Details</div>
                <div class="form-group">
                    <label for="license_type">License Type *</label>
                    <select id="license_type" name="license_type" required>
                        <option value="">Select license type</option>
                        <option value="Light">Light</option>
                        <option value="Heavy">Heavy</option>
                        <option value="Motorbike">Motorbike</option>
                    </select>
                    <div class="error-text" id="license_type-error"></div>
                </div>
                <div class="form-group">
                    <label for="expected_salary">Expected Monthly Salary ($)</label>
                    <input type="number" id="expected_salary" name="expected_salary" placeholder="Enter desired salary" min="0" step="0.01">
                    <div class="error-text" id="expected_salary-error"></div>
                </div>
                <div class="form-group">
                    <label for="status">Availability Status *</label>
                    <select id="status" name="status" required>
                        <option value="1">Active (Available)</option>
                        <option value="0">Inactive (Not Available)</option>
                    </select>
                    <div class="error-text" id="status-error"></div>
                </div>
                <div class="form-group">
                    <label>Driver Rating</label>
                    <div class="rating-container" id="starRating">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                        <input type="hidden" id="rating" name="rating" value="0">
                    </div>
                    <div class="error-text" id="rating-error"></div>
                </div>
            </div>

            <!-- Location & Address -->
            <div class="form-section">
                <div class="section-title">Location & Address</div>
                <div class="form-group">
                    <label for="address">Full Address *</label>
                    <input type="text" id="address" name="address" placeholder="Enter your complete address" required>
                    <div class="error-text" id="address-error"></div>
                </div>
                <div class="form-group">
                    <label for="latitude">Latitude</label>
                    <input type="number" id="latitude" name="latitude" step="0.000001" min="-90" max="90" placeholder="Auto-filled when using location">
                    <div class="error-text" id="latitude-error"></div>
                </div>
                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="number" id="longitude" name="longitude" step="0.000001" min="-180" max="180" placeholder="Auto-filled when using location">
                    <div class="error-text" id="longitude-error"></div>
                </div>
                <button type="button" class="location-btn" id="locationBtn" onclick="getCurrentLocation()">
                    📍 Get My Current Location
                </button>
            </div>

            <!-- Submit -->
            <div class="submit-section">
                <button type="submit" class="submit-btn" id="submitBtn">
                    Join Our Driver Team
                </button>
            </div>

        </div>
    </form>
</div>

<script>
// Star rating functionality
const stars = document.querySelectorAll('.star');
const ratingInput = document.getElementById('rating');

stars.forEach(star => {
    star.addEventListener('click', function() {
        const rating = parseInt(this.getAttribute('data-rating'));
        ratingInput.value = rating;
        updateStars(rating);
    });
    
    star.addEventListener('mouseenter', function() {
        const rating = parseInt(this.getAttribute('data-rating'));
        updateStars(rating);
    });
});

document.getElementById('starRating').addEventListener('mouseleave', function() {
    const currentRating = parseInt(ratingInput.value);
    updateStars(currentRating);
});

function updateStars(rating) {
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

// Get current location
function getCurrentLocation() {
    const locationBtn = document.getElementById('locationBtn');
    
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by this browser.');
        return;
    }
    
    locationBtn.disabled = true;
    locationBtn.innerHTML = '<span class="loading"></span> Getting location...';
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
            document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
            
            locationBtn.disabled = false;
            locationBtn.innerHTML = '✅ Location Captured';
            
            setTimeout(() => {
                locationBtn.innerHTML = '📍 Get My Current Location';
            }, 3000);
        },
        function(error) {
            let errorMessage = 'Unable to get location. ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage += 'Location access denied by user.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage += 'Location information unavailable.';
                    break;
                case error.TIMEOUT:
                    errorMessage += 'Location request timed out.';
                    break;
                default:
                    errorMessage += 'An unknown error occurred.';
                    break;
            }
            
            alert(errorMessage + ' Please enter coordinates manually.');
            locationBtn.disabled = false;
            locationBtn.innerHTML = '📍 Get My Current Location';
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        }
    );
}

// Form submission with AJAX
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalBtnText = submitBtn.textContent;
    
    // Clear previous errors
    clearErrors();
    hideAlerts();
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading"></span> Registering...';
    
    const formData = new FormData(this);
    
    fetch('/cars/add_driver', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.getElementById('csrfToken').value,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessAlert('Driver registered successfully!');
            this.reset();
            ratingInput.value = 0;
            updateStars(0);
        } else if (data.errors) {
            showErrorAlert('Please fix the errors below.');
            displayErrors(data.errors);
        } else {
            showErrorAlert(data.message || 'Registration failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorAlert('Network error. Please check your connection and try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
});

// Utility functions
function clearErrors() {
    document.querySelectorAll('.form-group').forEach(group => {
        group.classList.remove('error');
    });
    document.querySelectorAll('.error-text').forEach(error => {
        error.textContent = '';
    });
}

function displayErrors(errors) {
    Object.keys(errors).forEach(field => {
        const errorElement = document.getElementById(field + '-error');
        const formGroup = document.querySelector(`[name="${field}"]`).closest('.form-group');
        
        if (errorElement && formGroup) {
            formGroup.classList.add('error');
            errorElement.textContent = errors[field][0];
        }
    });
}

function showSuccessAlert(message) {
    const alert = document.getElementById('successAlert');
    alert.querySelector('strong').nextSibling.textContent = ' ' + message;
    alert.style.display = 'block';
    alert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function showErrorAlert(message) {
    const alert = document.getElementById('errorAlert');
    document.getElementById('errorMessage').textContent = message;
    alert.style.display = 'block';
    alert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function hideAlerts() {
    document.getElementById('successAlert').style.display = 'none';
    document.getElementById('errorAlert').style.display = 'none';
}
</script>
</body>
</html>