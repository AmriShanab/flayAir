<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zoroval - Crew Shift Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Instrument Sans', sans-serif;
        }
        
        :root {
            --primary: #1a56db;
            --primary-dark: #0a2e6f;
            --secondary: #f8b803;
            --accent: #f53003;
            --light: #FDFDFC;
            --dark: #0a0a0a;
            --gray: #706f6c;
            --gray-light: #e3e3e0;
            --success: #10b981;
        }
        
        body {
            background: linear-gradient(135deg, var(--light) 0%, #f5f7fa 100%);
            color: var(--dark);
            min-height: 100vh;
            line-height: 1.6;
        }
        
        .dark body {
            background: linear-gradient(135deg, var(--dark) 0%, #1a1a2e 100%);
            color: var(--light);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            padding: 1.5rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .dark header {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .logo-icon {
            /* background: var(--primary); */
            /* color: white; */
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon img{
            width: 300px;
            height: auto;
        }

        .logo-icon-footer img{
            width: 200px;
            height: auto;
            color: white
        }
        
        .nav-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-outline {
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.3);
        }
        
        /* Hero Section */
        .hero {
            padding: 5rem 0;
            display: flex;
            align-items: center;
            min-height: 80vh;
        }
        
        .hero-content {
            flex: 1;
            max-width: 600px;
        }
        
        .hero-image {
            flex: 1;
            display: flex;
            justify-content: flex-end;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .dark .hero h1 {
            background: linear-gradient(135deg, var(--secondary) 0%, #ffd700 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero p {
            font-size: 1.25rem;
            color: var(--gray);
            margin-bottom: 2rem;
        }
        
        .dark .hero p {
            color: #a1a09a;
        }
        
        .hero-buttons {
            display: flex;
            gap: 15px;
            margin-top: 2rem;
        }
        
        .dashboard-preview {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: transform 0.5s ease;
        }
        
        .dark .dashboard-preview {
            background: #1a1a2e;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .dashboard-preview:hover {
            transform: perspective(1000px) rotateY(0) rotateX(0);
        }
        
        .dashboard-header {
            background: var(--primary);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dashboard-title {
            color: white;
            font-weight: 600;
        }
        
        .dashboard-content {
            padding: 20px;
        }
        
        .shift-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .dark .shift-item {
            border-bottom: 1px solid #3E3E3A;
        }
        
        .shift-time {
            font-weight: 600;
        }
        
        .shift-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-confirmed {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        
        /* Features Section */
        .features {
            padding: 5rem 0;
            background: rgba(0,0,0,0.02);
        }
        
        .dark .features {
            background: rgba(255,255,255,0.02);
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            font-weight: 700;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .dark .feature-card {
            background: #1a1a2e;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .icon-schedule {
            background: rgba(26, 86, 219, 0.1);
            color: var(--primary);
        }
        
        .icon-notifications {
            background: rgba(245, 184, 3, 0.1);
            color: var(--secondary);
        }
        
        .icon-team {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        /* CTA Section */
        .cta {
            padding: 5rem 0;
            text-align: center;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .cta p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }
        
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .btn-light {
            background: white;
            color: var(--primary);
        }
        
        .btn-light:hover {
            background: rgba(255,255,255,0.9);
        }
        
        .btn-transparent {
            border: 1px solid white;
            color: white;
        }
        
        .btn-transparent:hover {
            background: white;
            color: var(--primary);
        }
        
        /* Footer */
        footer {
            padding: 3rem 0;
            background: var(--dark);
            color: white;
            text-align: center;
        }
        
        .footer-content {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .copyright {
            margin-top: 2rem;
            opacity: 0.7;
            font-size: 0.9rem;
        }
        
        /* Responsive Design */
        @media (max-width: 968px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 3rem 0;
            }
            
            .hero-content {
                max-width: 100%;
                margin-bottom: 3rem;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .dashboard-preview {
                max-width: 100%;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }
        
        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
        }
        
        .delay-1 {
            animation-delay: 0.2s;
        }
        
        .delay-2 {
            animation-delay: 0.4s;
        }
        
        .delay-3 {
            animation-delay: 0.6s;
        }
    </style>
</head>
<body>
    <!-- Header with Login/Register Buttons -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">
                        <img src="{{ asset('images/Zoroval_logo_bg_remove.png') }}" alt="">
                    </div>
                    {{-- <span>Zoroval</span> --}}
                </div>
                <div class="nav-buttons">
                    <a href="{{ route('login') }}" class="btn btn-outline">Log In</a>
                    {{-- <a href="{{ route('register.form') }}" class="btn btn-primary">Register</a> --}}
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content fade-in-up">
                <h1>Streamline Your Crew Scheduling</h1>
                <p>Zoroval is the ultimate solution for managing your team's shifts, schedules, and availability. Save time, reduce errors, and keep your crew connected.</p>
                <div class="hero-buttons">
                    <a href="http://endevodigital.com/" class="btn btn-primary">Powered by Endevo Digital</a>
                    <a href="#features" class="btn btn-outline">Learn More</a>
                </div>
            </div>
            <div class="hero-image fade-in-up delay-1">
                {{-- <div class="dashboard-preview">
                    <div class="dashboard-header">
                        <div class="dashboard-title">Today's Shifts</div>
                        <div style="color: rgba(255,255,255,0.8); font-size: 0.9rem;">May 15, 2023</div>
                    </div>
                    <div class="dashboard-content">
                        <div class="shift-item">
                            <div>
                                <div class="shift-time">08:00 - 16:00</div>
                                <div>Flight Crew - FA123</div>
                            </div>
                            <div class="shift-status status-confirmed">Confirmed</div>
                        </div>
                        <div class="shift-item">
                            <div>
                                <div class="shift-time">10:00 - 18:00</div>
                                <div>Ground Staff - Terminal B</div>
                            </div>
                            <div class="shift-status status-confirmed">Confirmed</div>
                        </div>
                        <div class="shift-item">
                            <div>
                                <div class="shift-time">14:00 - 22:00</div>
                                <div>Cabin Crew - FA456</div>
                            </div>
                            <div class="shift-status status-pending">Pending</div>
                        </div>
                        <div class="shift-item">
                            <div>
                                <div class="shift-time">16:00 - 00:00</div>
                                <div>Maintenance - Hangar 3</div>
                            </div>
                            <div class="shift-status status-confirmed">Confirmed</div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title fade-in-up">Powerful Features</h2>
            <div class="features-grid">
                <div class="feature-card fade-in-up delay-1">
                    <div class="feature-icon icon-schedule">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 7V3M16 7V3M7 11H17M5 21H19C20.1046 21 21 20.1046 21 19V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V19C3 20.1046 3.89543 21 5 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Smart Scheduling</h3>
                    <p>Create and manage shifts with our intuitive drag-and-drop interface. Automate scheduling based on availability and qualifications.</p>
                </div>
                <div class="feature-card fade-in-up delay-2">
                    <div class="feature-icon icon-notifications">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 8C18 6.4087 17.3679 4.88258 16.2426 3.75736C15.1174 2.63214 13.5913 2 12 2C10.4087 2 8.88258 2.63214 7.75736 3.75736C6.63214 4.88258 6 6.4087 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.73 21C13.5542 21.3031 13.3019 21.5547 12.9982 21.7295C12.6946 21.9044 12.3504 21.9965 12 21.9965C11.6496 21.9965 11.3054 21.9044 11.0018 21.7295C10.6982 21.5547 10.4458 21.3031 10.27 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Real-time Notifications</h3>
                    <p>Keep your team informed with instant updates about shift changes, announcements, and important reminders.</p>
                </div>
                <div class="feature-card fade-in-up delay-3">
                    <div class="feature-icon icon-team">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 3.13C16.8604 3.3503 17.623 3.8507 18.1676 4.55231C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>Team Management</h3>
                    <p>Easily manage crew profiles, qualifications, and availability. Assign roles and permissions with precision.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2 class="fade-in-up">Ready to Transform Your Crew Management?</h2>
            <p class="fade-in-up delay-1">Join thousands of aviation professionals who trust Zoroval to manage their schedules efficiently.</p>
            <div class="cta-buttons fade-in-up delay-2">
                {{-- <a href="{{ route('login') }}" class="btn btn-light">Start Free Trial</a> --}}
                <a href="{{ route('login') }}" class="btn btn-transparent">Sign In</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="logo" style="justify-content: center; margin-bottom: 1rem;">
                    <div class="logo-icon-footer">
                        {{-- <img src="{{ asset('images/logo_white_version-removebg-preview.png') }}" alt=""> --}}
                        <span><a href="http://endevodigital.com/" style="text-decoration: none;">Powered by EndevoDigital</a></span>
                    </div>
                    
                </div>
                {{-- <p>Streamlining crew management for the aviation industry</p> --}}
                {{-- <div class="footer-links">
                    <a href="#">About</a>
                    <a href="#">Features</a>
                    <a href="#">Pricing</a>
                    <a href="#">Contact</a>
                    <a href="#">Privacy Policy</a>
                </div> --}}
                <div class="copyright">
                    &copy; 2025 Zoroval. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Simple fade-in animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-in-up');
            
            const fadeInOnScroll = function() {
                fadeElements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementVisible = 150;
                    
                    if (elementTop < window.innerHeight - elementVisible) {
                        element.classList.add('active');
                    }
                });
            };
            
            // Check on load
            fadeInOnScroll();
            
            // Check on scroll
            window.addEventListener('scroll', fadeInOnScroll);
        });
    </script>
</body>
</html>