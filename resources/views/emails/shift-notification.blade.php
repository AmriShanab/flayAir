<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Notification - Zoroval</title>
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
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .greeting i {
            color: #0a2e6f;
            font-size: 26px;
        }
        
        .message-text {
            background: #f0f9ff;
            border-left: 4px solid #0a2e6f;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 10px 10px 0;
            font-size: 16px;
            color: #2d3748;
            line-height: 1.6;
        }
        
        .shift-details {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .shift-details h2 {
            color: #2d3748;
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .shift-details h2 i {
            color: #0a2e6f;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }
        
        .detail-content {
            flex: 1;
        }
        
        .detail-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 4px;
        }
        
        .detail-value {
            font-size: 16px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .flight-info {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 10px;
            padding: 20px;
            margin-top: 15px;
            border: 1px solid #e2e8f0;
        }
        
        .flight-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .flight-header i {
            color: #0a2e6f;
            font-size: 20px;
        }
        
        .flight-header h3 {
            color: #2d3748;
            font-size: 18px;
        }
        
        .flight-details {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 15px;
        }
        
        .flight-origin, .flight-destination {
            text-align: center;
        }
        
        .flight-code {
            font-size: 20px;
            font-weight: 700;
            color: #0a2e6f;
            margin-bottom: 5px;
        }
        
        .flight-airport {
            font-size: 14px;
            color: #64748b;
        }
        
        .flight-arrow {
            color: #0a2e6f;
            font-size: 24px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .btn {
            flex: 1;
            min-width: 140px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(96, 81, 207, 0.3);
        }
        
        .btn-outline {
            background: white;
            color: #0a2e6f;
            border: 2px solid #0a2e6f;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(96, 81, 207, 0.4);
        }
        
        .salutation {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .salutation p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .team-name {
            color: #0a2e6f;
            font-weight: 700;
            font-size: 16px;
        }
        
        .email-footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-text {
            color: #64748b;
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
        
        .urgency-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fef3c7;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
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
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                flex: none;
            }
            
            .flight-details {
                grid-template-columns: 1fr;
                gap: 10px;
                text-align: center;
            }
            
            .flight-arrow {
                transform: rotate(90deg);
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="email-container">
        <!-- Email Header -->
        <div class="email-header">
            <img src="{{ asset('images/Zorovel Logo - Zorovel White Logo - Edited.png') }}" alt="Zoroval Logo" class="logo">
            <h1>Shift Notification</h1>
            <p>Your schedule has been updated</p>
        </div>
        
        <!-- Email Content -->
        <div class="email-content">
            <!-- Greeting -->
            <div class="greeting">
                <i class="fas fa-user-circle"></i>
                Hi {{ $shift->worker->first_name }},
            </div>
            
            <!-- Message Text -->
            <div class="message-text">
                {{ $messageText }}
            </div>
            
            <!-- Shift Details -->
            <div class="shift-details">
                <h2>
                    <i class="fas fa-calendar-alt"></i>
                    Shift Details
                </h2>
                
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">START TIME</div>
                            <div class="detail-value">{{ $shift->start_time->format('l, F j, Y \\a\\t g:i A') }}</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-stop-circle"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">END TIME</div>
                            <div class="detail-value">{{ $shift->end_time->format('l, F j, Y \\a\\t g:i A') }}</div>
                        </div>
                    </div>
                    
                    @if($shift->flight)
                    <div class="flight-info">
                        <div class="flight-header">
                            <i class="fas fa-plane"></i>
                            <h3>Flight Information</h3>
                        </div>
                        <div class="flight-details">
                            <div class="flight-origin">
                                <div class="flight-code">{{ $shift->flight->origin ?? 'N/A' }}</div>
                                <div class="flight-airport">Origin</div>
                            </div>
                            <div class="flight-arrow">
                                <i class="fas fa-long-arrow-alt-right"></i>
                            </div>
                            <div class="flight-destination">
                                <div class="flight-code">{{ $shift->flight->destination ?? 'N/A' }}</div>
                                <div class="flight-airport">Destination</div>
                            </div>
                        </div>
                        <div style="text-align: center; margin-top: 15px;">
                            <div class="detail-label">FLIGHT NUMBER</div>
                            <div class="detail-value">{{ $shift->flight->flight_number }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="https://lightblue-guanaco-383578.hostingersite.com/shifts" class="btn btn-primary mx-3" style="text-decoration: none; color: white;">
                    <i class="fas fa-calendar-check"></i>
                    View Schedule
                </a>
                <a href="#" class="btn btn-outline">
                    <i class="fas fa-question-circle"></i>
                    Get Help
                </a>
            </div>
            
            <!-- Salutation -->
            <div class="salutation">
                <p>Thank you for your dedication and hard work.<br>
                <span class="team-name">Shift Management Team</span></p>
            </div>
        </div>
        
        <!-- Email Footer -->
        <div class="email-footer">
            <p class="footer-text">This is an automated notification from the Zoroval Crew Portal.</p>
            <p class="footer-text">
                <a href="http://endevodigital.com/" class="footer-link">Powered by Endevo Digital</a>
            </p>
        </div>
    </div>
</body>
</html>