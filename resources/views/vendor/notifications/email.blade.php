<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Zoroval</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
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
        
        .logo {
            width: 200px;
            margin: 0 auto 20px;
            display: block;
            filter: brightness(0) invert(1);
        }
        
        .email-header h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        
        .email-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            position: relative;
            z-index: 1;
        }
        
        .email-content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
        }
        
        .greeting.error {
            color: #e53e3e;
        }
        
        .intro-lines {
            margin-bottom: 30px;
        }
        
        .intro-lines p {
            margin-bottom: 15px;
            color: #4a5568;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .action-button {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%);
            color: white;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(96, 81, 207, 0.3);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(96, 81, 207, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        }
        
        .btn-error {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        }
        
        .outro-lines {
            margin: 30px 0;
        }
        
        .outro-lines p {
            margin-bottom: 15px;
            color: #4a5568;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .salutation {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        
        .salutation p {
            color: #718096;
            font-size: 14px;
        }
        
        .app-name {
            color: #0a2e6f;
            font-weight: 700;
            font-size: 16px;
        }
        
        .subcopy {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            font-size: 14px;
            color: #4a5568;
        }
        
        .subcopy p {
            margin-bottom: 10px;
        }
        
        .break-all {
            word-break: break-all;
            color: #0a2e6f;
            text-decoration: none;
            font-weight: 500;
        }
        
        .email-footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-text {
            color: #718096;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .footer-link {
            color: #0a2e6f;
            text-decoration: none;
            font-weight: 500;
        }
        
        .footer-link:hover {
            text-decoration: underline;
        }
        
        .security-notice {
            background: #fffaf0;
            border: 1px solid #fed7aa;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #744210;
        }
        
        .security-notice i {
            color: #dd6b20;
            margin-right: 8px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                border-radius: 10px;
            }
            
            .email-header {
                padding: 30px 20px;
            }
            
            .email-content {
                padding: 30px 20px;
            }
            
            .btn {
                padding: 14px 30px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Email Header -->
        <div class="email-header">
            <img src="{{ asset('images/Zoro-HQ-Big.png') }}" alt="Zoroval Logo" class="logo">
            <h1>Password Reset Request</h1>
            <p>Secure your account with a new password</p>
        </div>
        
        <!-- Email Content -->
        <div class="email-content">
            <!-- Greeting -->
            @if (! empty($greeting))
            <div class="greeting">{{ $greeting }}</div>
            @else
                @if ($level === 'error')
                <div class="greeting error">Whoops!</div>
                @else
                <div class="greeting">Hello!</div>
                @endif
            @endif
            
            <!-- Intro Lines -->
            <div class="intro-lines">
                @foreach ($introLines as $line)
                <p>{{ $line }}</p>
                @endforeach
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <p><i class="fas fa-shield-alt"></i> For your security, this password reset link will expire in 24 hours.</p>
            </div>
            
            <!-- Action Button -->
            @isset($actionText)
            <div class="action-button">
                <?php
                    $color = match ($level) {
                        'success', 'error' => $level,
                        default => 'primary',
                    };
                ?>
                <a href="{{ $actionUrl }}" class="btn btn-{{ $color }}">
                    {{ $actionText }}
                </a>
            </div>
            @endisset
            
            <!-- Outro Lines -->
            <div class="outro-lines">
                @foreach ($outroLines as $line)
                <p>{{ $line }}</p>
                @endforeach
            </div>
            
            <!-- Salutation -->
            <div class="salutation">
                @if (! empty($salutation))
                <p>{{ $salutation }}</p>
                @else
                <p>Regards,<br>
                <span class="app-name">{{ config('app.name') }}</span></p>
                @endif
            </div>
            
            <!-- Subcopy -->
            @isset($actionText)
            <div class="subcopy">
                <p>If you're having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below into your web browser:</p>
                <a href="{{ $actionUrl }}" class="break-all">{{ $displayableActionUrl }}</a>
            </div>
            @endisset
        </div>
        
        <!-- Email Footer -->
        <div class="email-footer">
            <p class="footer-text">This is an automated message from the Zoroval Crew Portal.</p>
            <p class="footer-text">
                <a href="http://endevodigital.com/" class="footer-link">Powered by Endevo Digital</a>
            </p>
        </div>
    </div>
</body>
</html>