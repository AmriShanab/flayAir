<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlayAir Airways - Crew Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f7fa; color: #333; display: flex; min-height: 100vh; }

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

        /* Main Content */
        .main-content { flex: 1; margin-left: 260px; padding: 30px; }
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

        /* Responsive */
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

        .sidebar-menu a { text-decoration: none; color: #fff; }
    </style>
</head>
<body>
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

    <!-- Main -->
    <div class="main-content">
        <div class="header">
            <div class="user-welcome">
                <h1>Welcome, {{ auth()->user()->name }}! 🎉</h1>
                <p>You are logged in successfully. Here's your schedule for today.</p>
            </div>
          <div class="user-info">
    <!-- Avatar: first letters of user's name -->
    <div class="user-avatar">
        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
    </div>

    <div>
        <!-- User's full name -->
        <div style="font-weight: 500;">
            {{ auth()->user()->name }}
        </div>

        <!-- User's role or position -->
        <div style="font-size: 13px; color: #666;">
            {{ auth()->user()->position ?? auth()->user()->role }}
        </div>
    </div>
</div>

        </div>

        <!-- Dashboard Cards -->
        {{-- <div class="dashboard-cards">
            <div class="card">
                <div class="card-header"><div class="card-title">Upcoming Shifts</div><div class="card-icon"><i class="fas fa-calendar"></i></div></div>
                <div class="card-value">3</div><div class="card-text">Next 7 days</div>
            </div>
            <div class="card">
                <div class="card-header"><div class="card-title">Flight Hours</div><div class="card-icon"><i class="fas fa-clock"></i></div></div>
                <div class="card-value">42<span style="font-size: 18px;">h</span></div><div class="card-text">This month</div>
            </div>
            <div class="card">
                <div class="card-header"><div class="card-title">Time Off</div><div class="card-icon"><i class="fas fa-umbrella-beach"></i></div></div>
                <div class="card-value">5<span style="font-size: 18px;">d</span></div><div class="card-text">Available</div>
            </div>
        </div> --}}

        <!-- Upcoming Shifts -->
        {{-- <div class="shifts-section">
            <div class="section-header">
                <div class="section-title">Upcoming Shifts</div>
                <a href="{{ route('shifts.index') }}" class="view-all">View All</a>
            </div>
            <div class="shifts-list">
                <div class="shift-item"><div class="shift-info"><h4>Flight SL231 - JFK to LHR</h4><p>Boeing 777 • First Officer</p></div><div class="shift-time">08:00 - 16:30</div></div>
                <div class="shift-item"><div class="shift-info"><h4>Flight SL145 - LHR to CDG</h4><p>Airbus A320 • Captain</p></div><div class="shift-time">09:15 - 12:45</div></div>
                <div class="shift-item"><div class="shift-info"><h4>Flight SL402 - CDG to FRA</h4><p>Airbus A320 • Captain</p></div><div class="shift-time">14:00 - 16:15</div></div>
            </div>
        </div> --}}

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.createElement('div');
            menuToggle.className = 'menu-toggle';
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            document.body.appendChild(menuToggle);
            const sidebar = document.querySelector('.sidebar');
            menuToggle.addEventListener('click', function() { sidebar.classList.toggle('active'); });
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>
