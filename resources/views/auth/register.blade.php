<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlayAir Airways - Crew Registration</title>
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
        
        .register-container {
            display: flex;
            width: 950px;
            max-width: 100%;
            height: auto;
            min-height: 550px;
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
            overflow-y: auto;
            max-height: 550px;
        }
        
        .form-section h2 {
            color: #0a2e6f;
            margin-bottom: 25px;
            font-size: 28px;
            position: relative;
            margin-top: 28px;
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
            margin-bottom: 18px;
            position: relative;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #0a2e6f;
            font-size: 14px;
        }
        
        .input-group input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
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
            top: 40px;
            color: #1a56db;
            font-size: 17px;
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
            display: none;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-error ul {
            margin-left: 20px;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 15px;
        }
        
        .login-link a {
            color: #1a56db;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .password-rules {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        @media (max-width: 950px) {
            .register-container {
                flex-direction: column;
                height: auto;
            }
            
            .brand-section {
                padding: 25px 20px;
            }
            
            .form-section {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="brand-section">
            <div class="brand-logo">
                <i class="fas fa-plane"></i>
            </div>
            <h1>FlayAir Airways</h1>
            <p>Join our crew management system and streamline your scheduling</p>
        </div>
        
        <div class="form-section">
            <h2>Create Account</h2>
            
            <!-- Error Messages (hidden by default, shown only when needed) -->
            <div class="alert alert-error" id="error-message">
                <i class="fas fa-exclamation-circle"></i> 
                <ul id="error-list">
                    <!-- Errors will be inserted here dynamically -->
                </ul>
            </div>
            
            <form method="POST" action="{{ route('register.post') }}" id="registration-form">
                @csrf
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <i class="fas fa-user"></i>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" value="{{ old('name') }}" required>
                </div>
                
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="crew.member@FlayAirairways.com" value="{{ old('email') }}" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Create a secure password" required>
                    <p class="password-rules">Use at least 8 characters with a mix of letters, numbers & symbols</p>
                </div>
                
                <div class="input-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                </div>
                
                <button type="submit">Create Account</button>
            </form>
            
            <div class="login-link">
                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </div>
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
        
        // In a real application, you would show errors based on server response
        // This is just a simulation of how it would work
        document.getElementById('registration-form').addEventListener('submit', function(e) {
            // For demonstration purposes, we're preventing actual form submission
            e.preventDefault();
            
            // Clear previous errors
            document.getElementById('error-list').innerHTML = '';
            document.getElementById('error-message').style.display = 'none';
            
            // Simulate validation errors (remove this in production)
            const simulateErrors = false; // Set to true to see error display
            
            if (simulateErrors) {
                // Simulate some validation errors
                const errors = [
                    'The name field is required',
                    'The email has already been taken',
                    'The password must be at least 8 characters'
                ];
                
                errors.forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    document.getElementById('error-list').appendChild(li);
                });
                
                // Show error message
                document.getElementById('error-message').style.display = 'block';
                
                // Scroll to errors
                document.getElementById('error-message').scrollIntoView({ behavior: 'smooth' });
            } else {
                // In a real application, you would allow the form to submit
                // this.submit();
                alert('Registration successful! In a real application, this would redirect.');
            }
        });
    </script>
</body>
</html>