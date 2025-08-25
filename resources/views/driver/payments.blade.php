<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - Driver Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d1b69 100%);
            min-height: 100vh;
            color: white;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px 0;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, #7e22ce, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(45deg, #7e22ce, #a855f7);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(126, 34, 206, 0.3);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(126, 34, 206, 0.4);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: linear-gradient(145deg, rgba(26, 26, 26, 0.9), rgba(45, 27, 105, 0.9));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(126, 34, 206, 0.3);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .summary-card h3 {
            color: #e5e7eb;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-card .amount {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .summary-card .count {
            color: #a855f7;
            font-size: 0.875rem;
        }

        .total-earned .amount {
            background: linear-gradient(45deg, #10b981, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .pending-amount .amount {
            background: linear-gradient(45deg, #f59e0b, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .completed-payments .amount {
            background: linear-gradient(45deg, #3b82f6, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .payments-table {
            background: linear-gradient(145deg, rgba(26, 26, 26, 0.9), rgba(45, 27, 105, 0.9));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(126, 34, 206, 0.3);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .table-header {
            background: linear-gradient(90deg, #7e22ce, #a855f7);
            padding: 20px;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .table-content {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(126, 34, 206, 0.2);
        }

        th {
            background: rgba(126, 34, 206, 0.1);
            font-weight: 600;
            color: #a855f7;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }

        td {
            color: #e5e7eb;
        }

        .payment-id {
            font-weight: 600;
            color: #a855f7;
        }

        .amount-cell {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-completed {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-partial {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .no-payments {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .no-payments svg {
            width: 64px;
            height: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .date-cell {
            font-size: 0.875rem;
            color: #9ca3af;
        }

        .icon {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .filter-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 1px solid rgba(126, 34, 206, 0.3);
            background: rgba(126, 34, 206, 0.1);
            color: #a855f7;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background: rgba(126, 34, 206, 0.3);
            color: white;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .header h1 {
                font-size: 2rem;
            }

            .summary-cards {
                grid-template-columns: 1fr;
            }

            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-buttons {
                justify-content: center;
            }

            th, td {
                padding: 10px 15px;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment History</h1>
            <a href="/driver/dashboard?role=driver&id={{ $driverId }}" class="back-btn">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Back to Dashboard
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card total-earned">
                <h3>Total Earned</h3>
                <div class="amount">${{ number_format($totalEarned, 2) }}</div>
                <div class="count">{{ $completedPayments }} completed payments</div>
            </div>
            <div class="summary-card pending-amount">
                <h3>Pending Amount</h3>
                <div class="amount">${{ number_format($pendingAmount, 2) }}</div>
                <div class="count">{{ $pendingPayments }} pending payments</div>
            </div>
            <div class="summary-card completed-payments">
                <h3>Partial Payments</h3>
                <div class="amount">${{ number_format($partialAmount, 2) }}</div>
                <div class="count">{{ $partialPayments }} partial payments</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div>
                <span style="color: #e5e7eb; font-weight: 600;">Filter by status:</span>
            </div>
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterPayments('all')">All</button>
                <button class="filter-btn" onclick="filterPayments('completed')">Completed</button>
                <button class="filter-btn" onclick="filterPayments('pending')">Pending</button>
                <button class="filter-btn" onclick="filterPayments('partial')">Partial</button>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="payments-table">
            <div class="table-header">
                Payment History
            </div>
            <div class="table-content">
                @if(count($payments) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Car Owner</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="paymentsTableBody">
                        @foreach($payments as $payment)
                        <tr class="payment-row" data-status="{{ $payment->final_payment == 1 ? 'completed' : ($payment->first_payment == 1 ? 'partial' : 'pending') }}">
                            <td class="payment-id">#{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="amount-cell">${{ number_format($payment->paid_amount, 2) }}</td>
                            <td>
                                @if($payment->final_payment == 1)
                                    <span class="status-badge status-completed">Completed</span>
                                @elseif($payment->first_payment == 1)
                                    <span class="status-badge status-partial">Partial</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </td>
                            <td>{{ $payment->car_owner }}</td>
                            <td class="date-cell">{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="no-payments">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                    </svg>
                    <h3>No Payment History</h3>
                    <p>You don't have any payment records yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function filterPayments(status) {
            const rows = document.querySelectorAll('.payment-row');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // Update button states
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter rows
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Add some hover effects to table rows
        document.querySelectorAll('.payment-row').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(126, 34, 206, 0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'transparent';
            });
        });
    </script>
</body>
</html>