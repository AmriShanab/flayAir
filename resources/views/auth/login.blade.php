<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlayAir Airways - Crew Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            padding: 20px;
        }
        
        .login-container {
            display: flex;
            width: 900px;
            max-width: 100%;
            height: auto;
            min-height: 500px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        
        .brand-section {
            flex: 1;
            background: linear-gradient(rgba(10, 46, 111, 0.8), rgba(10, 46, 111, 0.8)), 
                        url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1074&q=80') center/cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            position: relative;
        }
        
        .brand-logo {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .brand-logo i {
            font-size: 50px;
            color: #1a56db;
        }
        
        .brand-section h1 {
            font-size: 28px;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .brand-section p {
            text-align: center;
            font-size: 16px;
            line-height: 1.5;
        }
        
        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        
        .form-section h2 {
            color: #0a2e6f;
            margin-bottom: 30px;
            font-size: 28px;
            position: relative;
        }
        
        .form-section h2:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 4px;
            background: #1a56db;
            border-radius: 2px;
        }
        
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #0a2e6f;
        }
        
        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .input-group input:focus {
            border-color: #1a56db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.2);
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 42px;
            color: #1a56db;
            font-size: 18px;
        }
        
        button {
            background: #1a56db;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
            margin-top: 10px;
        }
        
        button:hover {
            background: #0a2e6f;
        }
        
        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none; /* Hidden by default */
        }
        
        .alert.visible {
            display: block; /* Show when needed */
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
        
        .alert-error ul {
            margin-left: 20px;
        }
        
        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }
        
        .forgot-password a {
            color: #1a56db;
            text-decoration: none;
            font-size: 14px;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .message-container {
            min-height: 150px;
        }
        
        @media (max-width: 950px) {
            .login-container {
                flex-direction: column;
                height: auto;
            }
            
            .brand-section {
                padding: 30px 20px;
            }
            
            .form-section {
                padding: 30px;
            }
            
            .message-container {
                min-height: auto;
                margin-bottom: 20px;
            }
        }
        
        /* Toggle for demonstration purposes */
        .demo-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }
        
        .demo-btn {
            padding: 8px 12px;
            background: #0a2e6f;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-section">
            <div class="brand-logo">
                <i class="fas fa-plane"></i>
            </div>
            <h1>FlayAir Airways</h1>
            <p>Crew Shift Management System</p>
        </div>
        
        <div class="form-section">
            <!-- Demo controls (would be removed in production) -->
            {{-- <div class="demo-controls">
                <button class="demo-btn" onclick="showMessage('success')">Show Success</button>
                <button class="demo-btn" onclick="showMessage('error')">Show Error</button>
                <button class="demo-btn" onclick="hideMessages()">Hide Messages</button>
            </div> --}}
            
            <h2>Login to Your Account</h2>
            
            <div class="message-container">
                <!-- Success Message (hidden by default) -->
                <div class="alert alert-success" id="success-message">
                    <i class="fas fa-check-circle"></i> Your password has been successfully reset!
                </div>
                
                <!-- Error Messages (hidden by default) -->
                <div class="alert alert-error" id="error-message">
                    <i class="fas fa-exclamation-circle"></i> 
                    <ul>
                        <li>Invalid credentials provided</li>
                        <li>Account is temporarily locked</li>
                    </ul>
                </div>
            </div>
            
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="crew.member@FlayAirairways.com" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
                
                <button type="submit">Login to Dashboard</button>
            </form>
        </div>
    </div>

    <script>
        // Simple animation for input focus
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('i').style.color = '#0a2e6f';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('i').style.color = '#1a56db';
            });
        });
        
        // Functions for demonstration purposes
        function showMessage(type) {
            hideMessages();
            document.getElementById(`${type}-message`).classList.add('visible');
        }
        
        function hideMessages() {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.classList.remove('visible');
            });
        }
        
        // In a real application, you would show messages based on server response
        // For example:
        /*
        @if(session('success'))
            document.getElementById('success-message').classList.add('visible');
        @endif
        
        @if($errors->any())
            document.getElementById('error-message').classList.add('visible');
        @endif
        
    </script>
</body>
</html>