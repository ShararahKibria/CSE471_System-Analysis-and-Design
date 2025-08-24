<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Owner Applications - Approval System</title>
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
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 4rem);
        }

        .header {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            font-weight: 600;
            border-bottom: 3px solid #3498db;
            padding-bottom: 1rem;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border-radius: 2px;
        }

        .loading {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
            font-size: 1.2rem;
        }

        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            padding: 1rem;
        }

        .card {
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .card-content {
            padding: 2rem;
        }

        .owner-name {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        .owner-email {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .info-grid {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 1rem;
            background: rgba(52, 152, 219, 0.05);
            border-radius: 10px;
            border-left: 4px solid #3498db;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: rgba(52, 152, 219, 0.1);
            transform: translateX(5px);
        }

        .info-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .info-value {
            color: #7f8c8d;
            font-weight: 500;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn {
            flex: 1;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-approve {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }

        .btn-approve:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-reject:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }

        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .empty-state {
            text-align: center;
            color: #7f8c8d;
            font-size: 1.2rem;
            padding: 4rem 2rem;
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .alert {
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            display: none;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
            border-left: 4px solid #2ecc71;
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.1);
            color: #c0392b;
            border-left: 4px solid #e74c3c;
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: rgba(241, 196, 15, 0.2);
            color: #f39c12;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
                margin: 0.5rem;
            }
            
            .card-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .header {
                font-size: 2rem;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="header">Car Owner Applications</h1>
        
        <div id="alertContainer">
            <div id="successAlert" class="alert alert-success"></div>
            <div id="errorAlert" class="alert alert-error"></div>
        </div>

        <div id="loadingContainer" class="loading">
            <div class="spinner"></div>
            <div>Loading applications...</div>
        </div>

        <div id="emptyState" class="empty-state" style="display: none;">
            <div class="empty-icon">🚗</div>
            <h3>No Pending Applications</h3>
            <p>All car owner applications have been processed.</p>
        </div>

        <div id="cardContainer" class="card-container" style="display: none;">
            <!-- Cards will be populated by JavaScript -->
        </div>
    </div>

    <script>
        // Global variables
        let carOwners = [];
        
        // DOM elements
        const loadingContainer = document.getElementById('loadingContainer');
        const emptyState = document.getElementById('emptyState');
        const cardContainer = document.getElementById('cardContainer');
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        // Utility functions
        function showAlert(type, message) {
            const alert = type === 'success' ? successAlert : errorAlert;
            alert.textContent = message;
            alert.style.display = 'block';
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);

            // Scroll to top to show alert
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function hideAlerts() {
            successAlert.style.display = 'none';
            errorAlert.style.display = 'none';
        }

        // Fetch car owners data
        async function fetchCarOwners() {
            try {
                loadingContainer.style.display = 'block';
                emptyState.style.display = 'none';
                cardContainer.style.display = 'none';

                const response = await fetch('http://127.0.0.1:8000/car_approval', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                carOwners = data;
                
                loadingContainer.style.display = 'none';
                
                if (carOwners.length === 0) {
                    emptyState.style.display = 'block';
                } else {
                    renderCarOwners();
                }

            } catch (error) {
                console.error('Error fetching car owners:', error);
                loadingContainer.style.display = 'none';
                showAlert('error', 'Failed to load applications. Please try again.');
            }
        }

        // Render car owners cards
        function renderCarOwners() {
            cardContainer.innerHTML = '';
            
            carOwners.forEach(owner => {
                const card = createOwnerCard(owner);
                cardContainer.appendChild(card);
            });
            
            cardContainer.style.display = 'grid';
        }

        // Create individual car owner card
        function createOwnerCard(owner) {
            const card = document.createElement('div');
            card.className = 'card';
            
            card.innerHTML = `
                <div class="card-header">
                    <h3 class="owner-name">${escapeHtml(owner.full_name || 'N/A')}</h3>
                    <p class="owner-email">${escapeHtml(owner.email || 'N/A')}</p>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">${escapeHtml(owner.phone || 'N/A')}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Location:</span>
                            <span class="info-value">${escapeHtml(owner.location || 'N/A')}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Car:</span>
                            <span class="info-value">${escapeHtml(`${owner.car_make || ''} ${owner.car_model || ''}`.trim() || 'N/A')}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Year & Color:</span>
                            <span class="info-value">${escapeHtml(`${owner.car_year || ''} - ${owner.car_color || ''}`.replace(' - ', ' - ') || 'N/A')}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">License Plate:</span>
                            <span class="info-value">${escapeHtml(owner.license_plate || 'N/A')}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">VIN:</span>
                            <span class="info-value">${escapeHtml(owner.vin || 'N/A')}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Condition:</span>
                            <span class="info-value">${escapeHtml(owner.car_condition || 'N/A')}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Mileage:</span>
                            <span class="info-value">${owner.mileage ? escapeHtml(owner.mileage + ' km/l') : 'N/A'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Payment Method:</span>
                            <span class="info-value">${escapeHtml(owner.payment_method || 'N/A')}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value">
                                <span class="status-badge status-pending">Pending Approval</span>
                            </span>
                        </div>
                    </div>
                    <div class="button-group">
                        <button 
                            class="btn btn-approve" 
                            onclick="handleApprove(${owner.id}, '${escapeHtml(owner.full_name)}', this)"
                        >
                            Approve
                        </button>
                        <button 
                            class="btn btn-reject" 
                            onclick="handleReject(${owner.id}, '${escapeHtml(owner.full_name)}', this)"
                        >
                            Reject
                        </button>
                    </div>
                </div>
            `;
            
            return card;
        }

        // Handle approval
        async function handleApprove(ownerId, ownerName, button) {
            if (!confirm(`Are you sure you want to approve ${ownerName}?`)) {
                return;
            }

            const originalText = button.textContent;
            button.classList.add('btn-loading');
            button.disabled = true;

            try {
                const response = await fetch('http://127.0.0.1:8000/approve-car-owner', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: ownerId
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showAlert('success', `${ownerName} has been approved successfully!`);
                    // Remove the approved owner from the list
                    carOwners = carOwners.filter(owner => owner.id !== ownerId);
                    
                    if (carOwners.length === 0) {
                        cardContainer.style.display = 'none';
                        emptyState.style.display = 'block';
                    } else {
                        renderCarOwners();
                    }
                } else {
                    throw new Error(data.message || 'Approval failed');
                }

            } catch (error) {
                console.error('Error approving car owner:', error);
                showAlert('error', `Failed to approve ${ownerName}. Please try again.`);
            } finally {
                button.classList.remove('btn-loading');
                button.disabled = false;
                button.textContent = originalText;
            }
        }

        // Handle rejection
        async function handleReject(ownerId, ownerName, button) {
            if (!confirm(`Are you sure you want to reject ${ownerName}? This action cannot be undone.`)) {
                return;
            }

            const originalText = button.textContent;
            button.classList.add('btn-loading');
            button.disabled = true;

            try {
                const response = await fetch('http://127.0.0.1:8000/reject-car-owner', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: ownerId
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showAlert('success', `${ownerName} has been rejected.`);
                    // Remove the rejected owner from the list
                    carOwners = carOwners.filter(owner => owner.id !== ownerId);
                    
                    if (carOwners.length === 0) {
                        cardContainer.style.display = 'none';
                        emptyState.style.display = 'block';
                    } else {
                        renderCarOwners();
                    }
                } else {
                    throw new Error(data.message || 'Rejection failed');
                }

            } catch (error) {
                console.error('Error rejecting car owner:', error);
                showAlert('error', `Failed to reject ${ownerName}. Please try again.`);
            } finally {
                button.classList.remove('btn-loading');
                button.disabled = false;
                button.textContent = originalText;
            }
        }

        // Utility function to escape HTML
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            hideAlerts();
            fetchCarOwners();
        });

        // Refresh data every 30 seconds
        setInterval(fetchCarOwners, 30000);
    </script>
</body>
</html>