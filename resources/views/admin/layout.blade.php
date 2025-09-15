<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #10b981;
            --light-bg: #f9fafb;
            --dark-text: #1f2937;
            --mid-text: #6b7280;
            --light-text: #9ca3af;
            --border: #e5e7eb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --sidebar-bg: #1f2937;
            --sidebar-hover: #374151;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: white;
            transition: all 0.3s ease;
            position: fixed;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h4 {
            font-weight: 600;
            color: white;
        }
        
        .sidebar .nav-link {
            color: #d1d5db;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: var(--sidebar-hover);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 1.5rem;
            text-align: center;
        }
        
        .logout-btn {
            background: none;
            border: none;
            color: #d1d5db;
            text-align: left;
            width: 100%;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
            min-height: 100vh;
        }
        
        /* Dashboard Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }
        
        .dashboard-header h2 {
            font-weight: 700;
            color: var(--dark-text);
            margin: 0;
        }
        
        .date-display {
            color: var(--mid-text);
            font-size: 0.95rem;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .card:hover {
            box-shadow: var(--hover-shadow);
        }
        
        .card-header {
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header i {
            font-size: 1.25rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-footer {
            background-color: white;
            border-top: 1px solid var(--border);
            padding: 1rem 1.5rem;
            border-radius: 0 0 0.75rem 0.75rem !important;
            text-align: center;
        }
        
        .card-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .card-footer a:hover {
            color: var(--primary-dark);
        }
        
        /* Table Styles */
        .table-container {
            overflow-x: auto;
            border-radius: 0.5rem;
        }
        
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 0;
        }
        
        .table th {
            background-color: #f9fafb;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: var(--mid-text);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
        }
        
        .table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            color: var(--dark-text);
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Badge Styles */
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .bg-success {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
        }
        
        .bg-secondary {
            background-color: #f3f4f6 !important;
            color: var(--mid-text) !important;
        }
        
        .bg-warning {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }
        
        .bg-danger {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
        }
        
        /* Stats Section */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-icon.workers {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }
        
        .stat-icon.shifts {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .stat-icon.flights {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }
        
        .stat-info h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark-text);
        }
        
        .stat-info p {
            color: var(--mid-text);
            margin: 0;
            font-size: 0.9rem;
        }
        
        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
        }
        
        .add-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: var(--card-shadow);
            text-decoration: none;
        }
        
        .add-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
            color: white;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid var(--success);
        }
        
        .alert .btn-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: inherit;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }
        
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .btn-edit {
            background-color: #fef3c7;
            color: #f59e0b;
        }
        
        .btn-edit:hover {
            background-color: #fde68a;
            transform: scale(1.05);
        }
        
        .btn-delete {
            background-color: #fee2e2;
            color: #ef4444;
            border: none;
        }
        
        .btn-delete:hover {
            background-color: #fecaca;
            transform: scale(1.05);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--mid-text);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--light-text);
        }
        
        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .pagination {
            display: flex;
            gap: 0.5rem;
        }
        
        .page-link {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid var(--border);
            color: var(--primary);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .page-link:hover {
            background-color: var(--primary);
            color: white;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                min-width: 70px;
                max-width: 70px;
                text-align: center;
            }
            
            .sidebar-header h4, .sidebar .nav-link span, .logout-btn span {
                display: none;
            }
            
            .sidebar .nav-link, .logout-btn {
                justify-content: center;
                padding: 0.75rem;
            }
            
            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 1.5rem 1rem;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .dashboard-header, .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .table th, .table td {
                padding: 0.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .table-responsive {
                border-radius: 0.5rem;
                border: 1px solid var(--border);
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid var(--border);
                border-radius: 0.5rem;
            }

            .table td {
                display: block;
                text-align: right;
                padding: 0.75rem;
                border-bottom: 1px solid var(--border);
            }

            .table td:last-child {
                border-bottom: none;
            }

            .table td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                color: var(--mid-text);
            }

            .action-buttons {
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column">
        <div class="sidebar-header">
            <h4>Admin Panel</h4>
        </div>
        <div class="flex-grow-1 p-2">
            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.add.shifts') }}" class="nav-link {{ request()->routeIs('admin.add.shifts') ? 'active' : '' }}">
                        <i class="bi bi-calendar-plus"></i>
                        <span>Add Shift</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.add.flights') }}" class="nav-link {{ request()->routeIs('admin.add.flights') ? 'active' : '' }}">
                        <i class="bi bi-airplane"></i>
                        <span>Add Flight</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('workers.index') }}" class="nav-link {{ request()->routeIs('workers.index') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Manage Workers</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('shifts.index') }}" class="nav-link {{ request()->routeIs('shifts.index') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>View Shift Timeline</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>View Users</span>

                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('shifts.index') }}" class="nav-link {{ request()->routeIs('shifts.index') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week"></i>
                        <span>View Shifts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('flights.index') }}" class="nav-link {{ request()->routeIs('flights.index') ? 'active' : '' }}">
                        <i class="bi bi-airplane-engines"></i>
                        <span>View Flights</span>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Reports</span>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </a>
                </li> --}}
            </ul>
        </div>
        <div class="p-2 mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <h2>@yield('page-title', 'Admin Dashboard')</h2>
            <div class="date-display">
                <i class="bi bi-calendar"></i> {{ now()->format('l, F j, Y') }}
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert">
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert" style="background-color: #fee2e2; color: #b91c1c; border-left-color: #ef4444;">
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close">&times;</button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Close alert functionality
    document.querySelectorAll('.btn-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.alert').style.display = 'none';
        });
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.display = 'none';
        });
    }, 5000);
</script>
</body>
</html>