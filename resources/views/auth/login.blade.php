<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoroval - Crew Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #f5f7fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Background elements */
        .bg-shape-1 {
            position: absolute;
            top: -10%;
            right: -5%;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #6051cf 0%, #4a3cb0 100%);
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
        }
        
        .bg-shape-2 {
            position: absolute;
            bottom: -10%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #6051cf 0%, #4a3cb0 100%);
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
        }
        
        .login-container {
            display: flex;
            width: 1000px;
            max-width: 100%;
            height: auto;
            min-height: 600px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }
        
        .brand-section {
            flex: 1.2;
    background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%);

            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .brand-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
            transform: rotate(-10deg);
        }
        
        .brand-logo {
            width: 180px;
            height: 180px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            /* background: rgba(255, 255, 255, 0.1); */
            /* border-radius: 50%; */
            padding: 20px;
            z-index: 1;
        }
        
        .brand-logo img {
            width: 350px;
            height: auto;
            filter: brightness(0) invert(1);
        }
        
        .brand-section h1 {
            font-size: 32px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 700;
            z-index: 1;
        }
        
        .brand-section p {
            text-align: center;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 80%;
            z-index: 1;
        }
        
        .features {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 80%;
            z-index: 1;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .feature i {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .feature span {
            font-size: 14px;
        }
        
        .form-section {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-header {
            margin-bottom: 40px;
        }
        
        .form-header h2 {
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 32px;
            font-weight: 700;
        }
        
        .form-header p {
            color: #718096;
            font-size: 16px;
        }
        
        .input-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
        }
        
        .input-container {
            position: relative;
        }
        
        .input-container input {
            width: 100%;
            padding: 16px 16px 16px 50px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f8fafc;
        }
        
        .input-container input:focus {
            border-color: #3a7bd5;
            outline: none;
            background: white;
            box-shadow: 0 0 0 3px rgba(96, 81, 207, 0.1);
        }
        
        .input-container i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #3a7bd5;
            font-size: 18px;
            transition: color 0.3s;
        }
        
        .input-container input:focus + i {
            color: #3a7bd5;
        }
        
        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #3a7bd5;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .password-toggle:hover {
            color: #3a7bd5;
        }
        
        button {
             background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(96, 81, 207, 0.3);
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(96, 81, 207, 0.4);
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            display: none;
        }
        
        .alert.visible {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #f0fff4;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }
        
        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #feb2b2;
        }
        
        .alert-error ul {
            margin-left: 20px;
        }
        
        .forgot-password {
            text-align: right;
            margin-top: 15px;
        }
        
        .forgot-password a {
            color: #6051cf;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .forgot-password a:hover {
            color: #4a3cb0;
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
            
            .bg-shape-1, .bg-shape-2 {
                display: none;
            }
        }
        
        /* Animation for form elements */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-header, .input-group, button {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .input-group:nth-child(1) { animation-delay: 0.1s; }
        .input-group:nth-child(2) { animation-delay: 0.2s; }
        button { animation-delay: 0.3s; }
    </style>
</head>
<body>
    {{-- <div class="bg-shape-1"></div> --}}
    <div class="bg-shape-2"></div>
    
    <div class="login-container">
        <div class="brand-section">
            <div class="brand-logo">
                <img src="{{ asset('images/zoro-big-version.png') }}" alt="Zoroval Logo">
            </div>
            <h1>Welcome Back!</h1>
            <p>Manage your shifts with ease, anytime, anywhere.</p>
            
            {{-- <div class="features">
                <div class="feature">
                    <i class="fas fa-check-circle"></i>
                    <span>Easy shift management</span>
                </div>
                <div class="feature">
                    <i class="fas fa-check-circle"></i>
                    <span>Real-time notifications</span>
                </div>
                <div class="feature">
                    <i class="fas fa-check-circle"></i>
                    <span>Secure crew portal</span>
                </div>
            </div> --}}
        </div>
        
        <div class="form-section">
            <div class="form-header">
                <h2>Sign In</h2>
                <p>Enter your credentials to access your account</p>
            </div>
            
            <div class="message-container">
                <!-- Success message -->
                @if(session('success'))
                    <div class="alert alert-success visible">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- Error messages -->
                @if($errors->any())
                    <div class="alert alert-error visible">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>Please fix the following:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <div class="input-container">
                        <input type="email" id="email" name="email" placeholder="example@zoroval.com" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-container">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock"></i>
                        <span class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
                
                <button type="submit">Sign In</button>
            </form>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const toggleIcon = togglePassword.querySelector('i');

            togglePassword.addEventListener('click', function () {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                toggleIcon.classList.toggle('fa-eye');
                toggleIcon.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>