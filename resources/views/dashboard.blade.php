<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoroval - Crew Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f7fa; color: #333; display: flex; min-height: 100vh; flex-direction: column; }

        /* Enhanced Sidebar */
        .sidebar { 
            width: 260px; 
            background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%); 
            color: white; 
            height: 100vh; 
            position: fixed; 
            overflow-y: auto; 
            transition: all 0.3s ease; 
            box-shadow: 0 0 20px rgba(0,0,0,0.15); 
            z-index: 1000; 
        }
        
        /* Enhanced Sidebar Header */
        .sidebar-header { 
            padding: 1px 20px; 
            text-align: center; 
            /* border-bottom: 1px solid rgba(255,255,255,0.15);  */
            /* background: rgba(255,255,255,0.05); */
            /* backdrop-filter: blur(10px); */
        }
        
        /* Enhanced Logo Container */
        .sidebar-logo { 
            width: 220px; 
            height: 160px; 
            /* background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);  */
            /* border-radius: 50%;  */
            display: flex; 
            justify-content: center; 
            align-items: center; 
            /* margin: 0 auto 20px;  */
            /* padding: 15px; */
            /* box-shadow: 0 8px 25px rgba(0,0,0,0.2); */
            /* border: 3px solid rgba(255,255,255,0.3); */
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-logo::before {
            content: '';
            position: absolute;
            /* top: -50%;
            left: -50%;
            width: 200%;
            height: 200%; */
            /* background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent); */
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }
        
        /* .sidebar-logo:hover::before {
            transform: rotate(45deg) translate(50%, 50%);
        } */
/*         
        .sidebar-logo:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
            border-color: rgba(255,255,255,0.5);
        } */
        
        .sidebar-logo img { 
            width: 100%; 
            height: auto; 
            filter: brightness(1.1) contrast(1.1);
            transition: transform 0.3s ease;
        }
        
        .sidebar-logo:hover img {
            transform: scale(1.05);
        }
        
        .sidebar-header h2 { 
            font-size: 24px; 
            font-weight: 700; 
            margin-bottom: 5px;
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .sidebar-subtitle {
            font-size: 12px;
            opacity: 0.8;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 500;
        }

        .sidebar-menu { padding: 20px 0; }
        .menu-item { 
            padding: 16px 25px; 
            display: flex; 
            align-items: center; 
            transition: all 0.3s; 
            cursor: pointer; 
            border-left: 4px solid transparent; 
            margin: 5px 10px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        
        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .menu-item:hover::before {
            left: 100%;
        }
        
        .menu-item:hover { 
            background: rgba(255,255,255,0.12); 
            border-left: 4px solid #fff; 
            transform: translateX(5px);
        }
        
        .menu-item.active { 
            background: rgba(255,255,255,0.15); 
            border-left: 4px solid #fff; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .menu-item i { 
            margin-right: 15px; 
            font-size: 18px; 
            width: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .menu-item:hover i {
            transform: scale(1.2);
        }
        
        .menu-label { 
            margin: 25px 25px 12px; 
            font-size: 12px; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            opacity: 0.7;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 8px;
        }

        /* Main Content */
       .main-content { 
    display: flex; 
    flex-direction: column; 
    min-height: 100vh; 
    margin-left: 260px; 
    transition: margin-left 0.3s ease;
    background-color: #f5f7fa;
}
        
        .content-wrapper {
    flex: 1;
    padding: 30px;
}
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e0e6ed; }
        .user-welcome h1 { color: #0a2e6f; font-size: 28px; margin-bottom: 5px; }
        .user-welcome p { color: #666; font-size: 16px; }
        .user-info { display: flex; align-items: center; }
        .user-avatar { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%); display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; font-size: 20px; margin-right: 15px; }

        /* Dashboard Cards */
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 30px; }
        .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.3s, box-shadow 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .card-title { font-size: 18px; font-weight: 600; color: #0a2e6f; }
        .card-icon { width: 50px; height: 50px; border-radius: 12px; background: rgba(26, 86, 219, 0.1); display: flex; justify-content: center; align-items: center; color: #1a56db; font-size: 20px; }
        .card-value { font-size: 28px; font-weight: 700; color: #0a2e6f; margin-bottom: 5px; }
        .card-text { color: #666; font-size: 14px; }

        /* Shifts Section */
        .shifts-section { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section-title { font-size: 20px; font-weight: 600; color: #0a2e6f; }
        .view-all { color: #1a56db; text-decoration: none; font-weight: 500; }
        .view-all:hover { text-decoration: underline; }
        .shifts-list { display: grid; gap: 15px; }
        .shift-item { display: flex; justify-content: space-between; align-items: center; padding: 15px; border-radius: 8px; background: #f9fafc; transition: background 0.3s; }
        .shift-item:hover { background: #f0f4ff; }
        .shift-info h4 { color: #0a2e6f; margin-bottom: 5px; }
        .shift-info p { color: #666; font-size: 14px; }
        .shift-time { background: rgba(26,86,219,0.1); color: #1a56db; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; }

        /* Quick Actions */
        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .action-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; background: white; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: all 0.3s; text-decoration: none; color: #333; }
        .action-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); color: #1a56db; }
        .action-icon { width: 50px; height: 50px; border-radius: 50%; background: rgba(26,86,219,0.1); display: flex; justify-content: center; align-items: center; color: #1a56db; font-size: 20px; margin-bottom: 12px; }
        .action-text { font-weight: 500; }

        /* Logout */
        .logout-form { margin-top: 30px; text-align: center; }
        .logout-btn { background: transparent; color: #dc3545; border: 1px solid #dc3545; padding: 12px 25px; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s; }
        .logout-btn:hover { background: #dc3545; color: white; }

       /* Footer Styles (Fixed Properly) */
    .footer {
        background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%);
        color: white;
        padding: 20px 30px;
        text-align: center;
        border-top: 1px solid rgba(255,255,255,0.1);
        width: 100%;
        margin-left: 0;
        position: relative;
        margin-bottom: -1.3rem;
    }

    .footer-content {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .footer-link {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: 6px;
        background: rgba(255,255,255,0.1);
    }

    .footer-link:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .footer-icon {
        font-size: 16px;
    }

    .footer-brand {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Responsive Fix */
    @media (max-width: 992px) {
        .main-content { margin-left: 80px; }
        .footer { margin-left: 80px; }
    }

    @media (max-width: 768px) {
        .main-content { margin-left: 0; }
        .footer { margin-left: 0; padding: 15px 20px; }
        .footer-content { flex-direction: column; gap: 8px; }
    }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { width: 80px; }
            .sidebar-header h2, .sidebar-subtitle, .menu-item span, .menu-label { display: none; }
            .sidebar-logo { width: 50px; height: 50px; padding: 8px; margin-bottom: 10px; }
            .sidebar-logo img { width: 100%; }
            .menu-item { justify-content: center; padding: 20px; margin: 2px 5px; }
            .menu-item i { margin-right: 0; font-size: 22px; }
            .main-content { margin-left: 80px; }
            .footer { margin-left: 80px; }
        }
        
        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 20px; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .header { flex-direction: column; align-items: flex-start; }
            .user-info { margin-top: 15px; }
            .footer { margin-left: 0; padding: 15px 20px; }
            .footer-content { flex-direction: column; gap: 8px; }
            .menu-toggle { 
                display: block; 
                position: fixed; 
                top: 20px; 
                left: 20px; 
                z-index: 1100; 
                background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%); 
                color: white; 
                width: 45px; 
                height: 45px; 
                border-radius: 50%; 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                box-shadow: 0 3px 10px rgba(0,0,0,0.2);
                cursor: pointer;
            }
        }

        .sidebar-menu a { text-decoration: none; color: #fff; }

        
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('images/Zoro-HQ-Big.png') }}" alt="Zoroval Logo">
            </div>
            {{-- <h2>Zoroval</h2> --}}
            {{-- <div class="sidebar-subtitle">Crew Management</div> --}}
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-label">Main</div>

            <a href="/dashboard" class="menu-item active">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>

            <a href="/shifts" class="menu-item">
                <i class="fas fa-calendar-alt"></i><span>My Shifts</span>
            </a>

            <a href="{{ route('worker.notifications') }}" class="menu-item">
                <i class="fas fa-bell"></i><span>Notifications</span>
            </a>

            <div class="menu-label">Account</div>

            <a href="{{ route('settings.view') }}" class="menu-item">
                <i class="fas fa-cog"></i><span>Settings</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
            <div class="header">
                <div class="user-welcome">
                    <h1>Welcome, {{ auth()->user()->name }}! 🎉</h1>
                    <p>You are logged in successfully. Here's your schedule for today.</p>
                </div>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-weight: 500;">
                            {{ auth()->user()->name }}
                        </div>
                        <div style="font-size: 13px; color: #666;">
                            {{ auth()->user()->position ?? auth()->user()->role }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="section-header"><div class="section-title">Quick Actions</div></div>
            <div class="quick-actions">
                <a href="{{ route('shifts.index') }}" class="action-btn">
                    <div class="action-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="action-text">View Shifts</div>
                </a>
            </div>

            <!-- Logout -->
            <div class="logout-form">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <a href="http://endevodigital.com/" target="_blank" class="footer-link">
                    <i class="fas fa-external-link-alt footer-icon"></i>
                    <span>Powered by</span>
                    <span class="footer-brand">Endevo Digital</span>
                </a>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.createElement('div');
            menuToggle.className = 'menu-toggle';
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            document.body.appendChild(menuToggle);
            
            const sidebar = document.querySelector('.sidebar');
            menuToggle.addEventListener('click', function() { 
                sidebar.classList.toggle('active'); 
            });
            
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = menuToggle.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                    }
                }
            });
        });
    </script>
</body>
</html>