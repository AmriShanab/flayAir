   <head> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
          <style> 
        .sidebar-menu a { text-decoration: none; color: #fff; }

        /* Sidebar */
        .sidebar { width: 260px; background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%); color: white; height: 100vh; position: fixed; overflow-y: auto; transition: all 0.3s ease; box-shadow: 0 0 15px rgba(0,0,0,0.1); z-index: 1000; }
        .sidebar-header { padding: 25px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-logo { width: 70px; height: 70px; background: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 15px; }
        .sidebar-logo i { font-size: 35px; color: #1a56db; }
        .sidebar-header h2 { font-size: 20px; font-weight: 600; }
        .sidebar-menu { padding: 20px 0; }
        .menu-item { padding: 14px 25px; display: flex; align-items: center; transition: all 0.3s; cursor: pointer; border-left: 4px solid transparent; }
        .menu-item:hover { background: rgba(255,255,255,0.1); border-left: 4px solid #fff; }
        .menu-item.active { background: rgba(255,255,255,0.15); border-left: 4px solid #fff; }
        .menu-item i { margin-right: 12px; font-size: 18px; }
        .menu-label { margin: 20px 25px 10px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.7; }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f7fa; color: #333; display: flex; min-height: 100vh; }
        
        @media (max-width: 992px) {
            .sidebar { width: 80px; }
            .sidebar-header h2, .menu-item span, .menu-label { display: none; }
            .menu-item { justify-content: center; padding: 20px; }
            .menu-item i { margin-right: 0; font-size: 22px; }
            .main-content { margin-left: 80px; }
        }
        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 20px; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .header { flex-direction: column; align-items: flex-start; }
            .user-info { margin-top: 15px; }
            .menu-toggle { display: block; position: fixed; top: 20px; left: 20px; z-index: 1100; background: #0a2e6f; color: white; width: 45px; height: 45px; border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0 3px 10px rgba(0,0,0,0.2); }
        }
    </style>
   </head>
   
   <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
                    <div class="sidebar-logo"><i class="fas fa-plane"></i></div>
                    <h2>FlayAir Airways</h2>
                </div>
            <div class="sidebar-menu">
            <div class="menu-label">Main</div>

            <a href="/dashboard" class="menu-item active">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>

            <a href="/shifts" class="menu-item">
                <i class="fas fa-calendar-alt"></i><span>My Shifts</span>
            </a>

            <div class="menu-label">Account</div>

            <a href="{{ route('settings.view') }}" class="menu-item">
                <i class="fas fa-cog"></i><span>Settings</span>
            </a>
        </div>

    </div>

  