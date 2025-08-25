<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cars Assigned - Mechanic Dashboard</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
      color: #e5e7eb;
      min-height: 100vh;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }

    /* Animated background particles */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                  radial-gradient(circle at 80% 20%, rgba(147, 51, 234, 0.1) 0%, transparent 50%),
                  radial-gradient(circle at 40% 40%, rgba(79, 70, 229, 0.1) 0%, transparent 50%);
      z-index: -1;
      animation: bgShift 20s ease-in-out infinite alternate;
    }

    @keyframes bgShift {
      0% { transform: translateX(-10px) translateY(-10px); }
      100% { transform: translateX(10px) translateY(10px); }
    }

    .container {
      max-width: 1400px;
      margin: 0 auto;
      position: relative;
    }

    .header {
      text-align: center;
      margin-bottom: 40px;
      padding: 20px 0;
    }

    .header h1 {
      font-size: 3rem;
      font-weight: 700;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 10px;
      text-shadow: 0 0 30px rgba(102, 126, 234, 0.3);
    }

    .header p {
      font-size: 1.2rem;
      color: #9ca3af;
      font-weight: 300;
    }

    /* Success message */
    .success-message {
      background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.2));
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: #10b981;
      padding: 15px 20px;
      border-radius: 12px;
      margin-bottom: 30px;
      backdrop-filter: blur(10px);
      display: flex;
      align-items: center;
      gap: 10px;
      animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Stats cards */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }

    .stat-card {
      background: linear-gradient(145deg, rgba(31, 41, 55, 0.8), rgba(55, 65, 81, 0.4));
      backdrop-filter: blur(10px);
      border: 1px solid rgba(75, 85, 99, 0.3);
      border-radius: 16px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      transition: left 0.5s;
    }

    .stat-card:hover::before {
      left: 100%;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      border-color: rgba(147, 51, 234, 0.5);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 5px;
    }

    .stat-label {
      color: #9ca3af;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    /* Table container */
    .table-container {
      background: linear-gradient(145deg, rgba(31, 41, 55, 0.9), rgba(17, 24, 39, 0.8));
      backdrop-filter: blur(20px);
      border: 1px solid rgba(75, 85, 99, 0.3);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
      position: relative;
    }

    .table-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 25px 30px;
      text-align: center;
    }

    .table-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: white;
      margin: 0;
    }

    .table-wrapper {
      overflow-x: auto;
      max-height: 70vh;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: transparent;
    }

    th {
      background: linear-gradient(135deg, rgba(55, 65, 81, 0.8), rgba(31, 41, 55, 0.9));
      color: #d1d5db;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 20px 15px;
      text-align: center;
      border-bottom: 2px solid rgba(75, 85, 99, 0.3);
      position: sticky;
      top: 0;
      z-index: 10;
    }

    td {
      padding: 20px 15px;
      text-align: center;
      border-bottom: 1px solid rgba(75, 85, 99, 0.2);
      transition: all 0.3s ease;
      position: relative;
    }

    tr {
      transition: all 0.3s ease;
      position: relative;
    }

    tr:hover {
      background: linear-gradient(90deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
      transform: scale(1.01);
    }

    tr:hover td {
      color: #f3f4f6;
    }

    .id-cell {
      font-weight: 700;
      color: #667eea;
      font-family: 'Courier New', monospace;
    }

    .owner-cell {
      font-weight: 600;
      color: #e5e7eb;
    }

    .date-cell {
      color: #9ca3af;
      font-size: 0.9rem;
    }

    .status-cell {
      font-weight: 600;
    }

    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      min-width: 80px;
    }

    .status-pending {
      background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(217, 119, 6, 0.3));
      color: #f59e0b;
      border: 1px solid rgba(245, 158, 11, 0.4);
    }

    .status-progress {
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.3));
      color: #3b82f6;
      border: 1px solid rgba(59, 130, 246, 0.4);
    }

    .status-done {
      background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.3));
      color: #10b981;
      border: 1px solid rgba(16, 185, 129, 0.4);
    }

    /* Action button */
    .action-form {
      display: inline-block;
    }

    .action-btn {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 25px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      position: relative;
      overflow: hidden;
      min-width: 100px;
    }

    .action-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .action-btn:hover::before {
      left: 100%;
    }

    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
    }

    .action-btn:disabled {
      background: linear-gradient(135deg, #6b7280, #4b5563);
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .action-btn:disabled::before {
      display: none;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #6b7280;
    }

    .empty-state svg {
      width: 80px;
      height: 80px;
      margin-bottom: 20px;
      opacity: 0.5;
    }

    .empty-state h3 {
      font-size: 1.5rem;
      margin-bottom: 10px;
      color: #9ca3af;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      body {
        padding: 10px;
      }

      .header h1 {
        font-size: 2rem;
      }

      .stats-container {
        grid-template-columns: 1fr;
      }

      th, td {
        padding: 12px 8px;
        font-size: 0.8rem;
      }

      .table-wrapper {
        font-size: 0.9rem;
      }
    }

    /* Loading animation */
    .loading {
      display: inline-block;
      width: 12px;
      height: 12px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top: 2px solid #fff;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-left: 8px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Cars Assigned</h1>
      <p>Manage your assigned vehicle services</p>
    </div>

    @if(session('success'))
    <div class="success-message">
      <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
      </svg>
      {{ session('success') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-container">
      <div class="stat-card">
        <div class="stat-number">{{ $cars->count() }}</div>
        <div class="stat-label">Total Assigned</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">{{ $cars->where('status', 'pending')->count() }}</div>
        <div class="stat-label">Pending</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">{{ $cars->where('status', 'in_progress')->count() }}</div>
        <div class="stat-label">In Progress</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">{{ $cars->where('status', 'done')->count() }}</div>
        <div class="stat-label">Completed</div>
      </div>
    </div>

    <!-- Table -->
    <div class="table-container">
      <div class="table-header">
        <h2 class="table-title">Service Assignments</h2>
      </div>
      
      <div class="table-wrapper">
        @if($cars->count() > 0)
        <table>
          <thead>
            <tr>
              <th>Job ID</th>
              <th>Car Owner</th>
              <th>Service Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($cars as $car)
            <tr>
              <td class="id-cell">#{{ str_pad($car->id, 4, '0', STR_PAD_LEFT) }}</td>
              <td class="owner-cell">{{ $car->owner_name }}</td>
              <td class="date-cell">{{ \Carbon\Carbon::parse($car->service_date)->format('M d, Y') }}</td>
              <td class="status-cell">
                @if($car->status === 'done')
                  <span class="status-badge status-done">Completed</span>
                @elseif($car->status === 'in_progress')
                  <span class="status-badge status-progress">In Progress</span>
                @else
                  <span class="status-badge status-pending">Pending</span>
                @endif
              </td>
              <td>
                <form action="{{ url('/mechanic/job-done/'.$car->id) }}" method="POST" class="action-form" onsubmit="showLoading(this)">
                  @csrf
                  <button type="submit" class="action-btn" {{ $car->status === 'done' ? 'disabled' : '' }}>
                    {{ $car->status === 'done' ? 'Completed' : 'Mark Done' }}
                  </button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="empty-state">
          <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
          </svg>
          <h3>No Cars Assigned</h3>
          <p>You don't have any service assignments yet.</p>
        </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    function showLoading(form) {
      const button = form.querySelector('.action-btn');
      if (!button.disabled) {
        button.innerHTML = 'Processing<span class="loading"></span>';
        button.disabled = true;
      }
    }

    // Add some interactive effects
    document.addEventListener('DOMContentLoaded', function() {
      // Animate table rows on load
      const rows = document.querySelectorAll('tbody tr');
      rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        setTimeout(() => {
          row.style.transition = 'all 0.5s ease';
          row.style.opacity = '1';
          row.style.transform = 'translateY(0)';
        }, index * 100);
      });

      // Auto-hide success message after 5 seconds
      const successMessage = document.querySelector('.success-message');
      if (successMessage) {
        setTimeout(() => {
          successMessage.style.opacity = '0';
          successMessage.style.transform = 'translateY(-20px)';
          setTimeout(() => {
            successMessage.remove();
          }, 500);
        }, 5000);
      }
    });
  </script>
</body>
</html>