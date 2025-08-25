<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Working Hours</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-title {
            color: #333;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-subtitle {
            color: #666;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .current-hours {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.95rem;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: #aaa;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #666;
            border: 2px solid #e1e5e9;
        }

        .btn-secondary:hover {
            background: #e9ecef;
            border-color: #dee2e6;
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .helper-text {
            color: #666;
            font-size: 0.875rem;
            margin-top: 8px;
            line-height: 1.4;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 30px 20px;
            }
            
            .form-title {
                font-size: 1.75rem;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title">Update Profile</h1>
            <p class="form-subtitle">Update your working hours</p>
            
            {{-- Show current working hours if available --}}
            @if(isset($driver->working_hours) && $driver->working_hours)
                <div class="current-hours">
                    Current Hours: {{ $driver->working_hours }}
                </div>
            @endif
        </div>

        {{-- Display success/error messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/driver/update-profile">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">
            
            <div class="form-group">
                <label for="working_hours" class="form-label">Working Hours</label>
                <textarea 
                    id="working_hours" 
                    name="working_hours" 
                    class="form-input"
                    placeholder="Enter your updated working hours (e.g., Monday-Friday: 9:00 AM - 5:00 PM, Saturday: 10:00 AM - 2:00 PM)"
                    required
                >{{ old('working_hours', $driver->working_hours ?? '') }}</textarea>
                <p class="helper-text">
                    Please provide your available working hours. Be specific about days and times 
                    to help customers know when you're available.
                </p>
            </div>

            <div class="button-group">
                <a href="/driver/dashboard?role=driver&id={{ $id }}" class="btn btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Update Hours
                </button>
            </div>
        </form>
    </div>

    <script>
        // Auto-focus on the textarea when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('working_hours').focus();
        });

        // Add some smooth form interactions
        const textarea = document.getElementById('working_hours');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.max(120, this.scrollHeight) + 'px';
        });
    </script>
</body>
</html>