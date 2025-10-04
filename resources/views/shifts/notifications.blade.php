@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<head>
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
            padding: 25px 20px; 
            text-align: center; 
            border-bottom: 1px solid rgba(255,255,255,0.15); 
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
        }
        
        /* Enhanced Logo Container */
        .sidebar-logo { 
            width: 140px; 
            height: 140px; 
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); 
            border-radius: 50%; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            margin: 0 auto 20px; 
            padding: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            border: 3px solid rgba(255,255,255,0.3);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-logo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }
        
        .sidebar-logo:hover::before {
            transform: rotate(45deg) translate(50%, 50%);
        }
        
        .sidebar-logo:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
            border-color: rgba(255,255,255,0.5);
        }
        
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
            background: rgba(255,255,255,0.2); 
            border-left: 4px solid #fff; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(5px);
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
            flex: 1; 
            margin-left: 200px; 
            padding: 30px; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
        }

        .content-wrapper {
            flex: 1;
        }

        /* Notifications */
        .notifications-container { 
            max-width: 1000px; 
            margin: 0 auto; 
            flex: 1;
        }

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e0e6ed; }
        .page-title { font-size: 28px; font-weight: 600; color: #0a2e6f; }
        .notification-actions { display: flex; gap: 15px; }
        .action-btn { padding: 10px 20px; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; }
        .mark-all-read { background: rgba(26, 86, 219, 0.1); color: #1a56db; border: none; }
        .mark-all-read:hover { background: rgba(26, 86, 219, 0.2); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(26, 86, 219, 0.2); }
        .clear-all { background: transparent; color: #dc3545; border: 1px solid #dc3545; }
        .clear-all:hover { background: #dc3545; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3); }

        .notification-list { background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
        .notification-item { padding: 20px; border-bottom: 1px solid #f0f4ff; display: flex; align-items: flex-start; transition: all 0.3s; }
        .notification-item:last-child { border-bottom: none; }
        .notification-item:hover { background: #f9fafc; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .notification-item.unread { background: #f0f7ff; border-left: 4px solid #1a56db; }

        .notification-icon { width: 50px; height: 50px; border-radius: 50%; background: rgba(26, 86, 219, 0.1); display: flex; justify-content: center; align-items: center; color: #1a56db; font-size: 20px; margin-right: 15px; flex-shrink: 0; transition: all 0.3s; }
        .notification-item:hover .notification-icon { transform: scale(1.1); background: rgba(26, 86, 219, 0.2); }
        .notification-content { flex: 1; }
        .notification-title { font-size: 18px; font-weight: 600; color: #0a2e6f; margin-bottom: 5px; }
        .notification-message { color: #666; margin-bottom: 10px; line-height: 1.5; }
        .notification-time { font-size: 14px; color: #888; display: flex; align-items: center; gap: 5px; }
        .notification-actions-item { display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap; }

        .btn-small { padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; border: none; outline: none; }
        .btn-small i { font-size: 12px; }
        .btn-view { background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.3); }
        .btn-view:hover { background: rgba(59, 130, 246, 0.2); transform: translateY(-1px); box-shadow: 0 2px 5px rgba(59, 130, 246, 0.2); }
        .btn-dismiss { background: rgba(107, 114, 128, 0.1); color: #6B7280; border: 1px solid rgba(107, 114, 128, 0.3); }
        .btn-dismiss:hover { background: rgba(107, 114, 128, 0.2); transform: translateY(-1px); box-shadow: 0 2px 5px rgba(107, 114, 128, 0.2); }
        .btn-ack { background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; border: 1px solid #10B981; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3); }
        .btn-ack:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); transform: translateY(-1px); box-shadow: 0 4px 8px rgba(16, 185, 129, 0.4); }

        .ack-status { color: #059669; font-weight: 600; display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(16, 185, 129, 0.1); border-radius: 6px; border: 1px dashed #10B981; }
        .ack-status i { color: #10B981; font-size: 14px; }
        .btn-small:active { transform: translateY(1px); }

        .empty-state { text-align: center; padding: 50px 20px; color: #888; }
        .empty-icon { font-size: 60px; color: #ddd; margin-bottom: 20px; }
        .empty-text { font-size: 18px; margin-bottom: 20px; }

        /* Enhanced Modal */
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow: auto; animation: fadeIn 0.3s ease; }
        .modal-content { background: #fff; margin: 10% auto; padding: 30px; border-radius: 15px; max-width: 500px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); animation: slideDown 0.3s ease; position: relative; }
        .modal-close { position: absolute; top: 15px; right: 20px; font-size: 24px; font-weight: bold; cursor: pointer; color: #666; transition: color 0.3s; }
        .modal-close:hover { color: #000; }
        .modal h2 { color: #0a2e6f; margin-bottom: 15px; font-size: 24px; }
        .modal p { margin-bottom: 10px; line-height: 1.6; }
        .modal-details { background: #f8fafc; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .modal-detail-item { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .modal-detail-label { font-weight: 600; color: #0a2e6f; }
        .modal-detail-value { color: #666; }

        @keyframes fadeIn { from {opacity: 0;} to {opacity: 1;} }
        @keyframes slideDown { from {transform: translateY(-20px); opacity: 0;} to {transform: translateY(0); opacity: 1;} }

        /* Footer Styles - Fixed for Full Width */
        .footer {
            background: linear-gradient(135deg, #0a2e6f 0%, #1a56db 100%);
            color: white;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            width: 100%;
            /* margin-left: 260px; */
            position: relative;
            left: 0;
            right: 0;
            margin-top: 2rem;
            margin-bottom: -3rem;
        }

        .footer {
    width: 100%;
    margin-left: 30px;
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

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { width: 80px; }
            .sidebar-header h2, .sidebar-subtitle, .menu-item span, .menu-label { display: none; }
            .sidebar-logo { width: 50px; height: 50px; padding: 8px; margin-bottom: 10px; }
            .sidebar-logo img { width: 100%; }
            .menu-item { justify-content: center; padding: 20px; margin: 2px 5px; }
            .menu-item i { margin-right: 0; font-size: 22px; }
            .main-content { margin-left: 80px; }
            .footer { 
                width: calc(100% - 80px);
                margin-left: 80px;
            }
        }
        
        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 20px; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .header { flex-direction: column; align-items: flex-start; }
            .user-info { margin-top: 15px; }
            .footer { 
                width: 100%;
                margin-left: 0;
                padding: 15px 20px;
            }
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
            .page-header { flex-direction: column; align-items: flex-start; gap: 15px; }
            .notification-actions { width: 100%; justify-content: space-between; }
            .notification-item { flex-direction: column; }
            .notification-icon { margin-bottom: 15px; }
        }
        
        @media (max-width: 480px) {
            .notification-actions-item { gap: 8px; }
            .btn-small { padding: 6px 10px; font-size: 12px; }
            .modal-content { margin: 5% auto; padding: 20px; }
        }

        .sidebar-menu a { text-decoration: none; color: #fff; }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('images/Zorovel-Black-HQ-Big.png') }}" alt="Zoroval Logo">
            </div>
            <h2>Zoroval</h2>
            <div class="sidebar-subtitle">Crew Management</div>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-label">Main</div>
            <a href="/dashboard" class="menu-item">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
            <a href="/shifts" class="menu-item">
                <i class="fas fa-calendar-alt"></i><span>My Shifts</span>
            </a>
            <a href="{{ route('worker.notifications') }}" class="menu-item active">
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
            <div class="notifications-container">
                <div class="page-header">
                    <h1 class="page-title">Your Notifications</h1>
                    <div class="notification-actions">
                        <button class="action-btn mark-all-read">
                            <i class="fas fa-check-double"></i> Mark all as read
                        </button>
                        <button class="action-btn clear-all">
                            <i class="fas fa-trash"></i> Clear all
                        </button>
                    </div>
                </div>

                <div class="notification-list">
                    @forelse($notifications as $notification)
                        <div class="notification-item {{ !$notification->read_at ? 'unread' : '' }}">
                            <div class="notification-icon">
                                @if(str_contains(strtolower($notification->title), 'shift'))
                                    <i class="fas fa-calendar-alt"></i>
                                @elseif(str_contains(strtolower($notification->title), 'alert') || str_contains(strtolower($notification->title), 'important'))
                                    <i class="fas fa-exclamation-circle"></i>
                                @elseif(str_contains(strtolower($notification->title), 'flight'))
                                    <i class="fas fa-plane"></i>
                                @else
                                    <i class="fas fa-bell"></i>
                                @endif
                            </div>
                            <div class="notification-content">
                                <h3 class="notification-title">{{ $notification->title }}</h3>
                                <p class="notification-message">{{ $notification->message }}</p>
                                <div class="notification-time">
                                    <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </div>
                                <div class="notification-actions-item">
                                    <button class="btn-small btn-view"
                                            data-id="{{ $notification->id }}"
                                            data-title="{{ $notification->title }}"
                                            data-message="{{ $notification->message }}"
                                            data-shift-start="{{ $notification->shift_start ? $notification->shift_start->format('F j, Y, g:i a') : 'N/A' }}"
                                            data-shift-end="{{ $notification->shift_end ? $notification->shift_end->format('F j, Y, g:i a') : 'N/A' }}"
                                            data-flight="{{ $notification->flight ? $notification->flight->flight_number : 'N/A' }}">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>

                                    <button class="btn-small btn-dismiss" data-id="{{ $notification->id }}">
                                        <i class="fas fa-times"></i> Dismiss
                                    </button>

                                    @if($notification->is_read == 0 || $notification->is_read == 1)
                                        <button class="btn-small btn-ack" data-id="{{ $notification->id }}">
                                            <i class="fas fa-check-circle"></i> Accept
                                        </button>
                                    @elseif($notification->is_read == 2)
                                        <span class="ack-status">
                                            <i class="fas fa-check-circle"></i> Accepted
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="far fa-bell-slash"></i>
                            </div>
                            <div class="empty-text">You have no notifications</div>
                            <p>When you have new notifications, they'll appear here</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Full Width Footer -->
        <footer class="footer">
            <div class="footer-content">
                <a href="http://endevodigital.com/" target="_blank" class="footer-link">
                    <i class="fas fa-external-link-alt footer-icon"></i>
                    <span>Powered by</span>
                    <span class="footer-brand">EndevoDigital</span>
                </a>
            </div>
        </footer>
    </div>

    <!-- Enhanced Notification Details Modal -->
    <div id="notificationModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2 id="modalTitle"></h2>
            <p id="modalMessage"></p>
            <div class="modal-details">
                <div class="modal-detail-item">
                    <span class="modal-detail-label">Start Time:</span>
                    <span id="modalShiftStart" class="modal-detail-value"></span>
                </div>
                <div class="modal-detail-item">
                    <span class="modal-detail-label">End Time:</span>
                    <span id="modalShiftEnd" class="modal-detail-value"></span>
                </div>
                <div class="modal-detail-item">
                    <span class="modal-detail-label">Flight Number:</span>
                    <span id="modalFlight" class="modal-detail-value"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('notificationModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const modalClose = document.querySelector('.modal-close');

        // View Details
        document.querySelectorAll('.btn-view').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                // Fill modal content
                modalTitle.textContent = this.dataset.title;
                modalMessage.textContent = this.dataset.message;
                document.getElementById('modalShiftStart').textContent = this.dataset.shiftStart;
                document.getElementById('modalShiftEnd').textContent = this.dataset.shiftEnd;
                document.getElementById('modalFlight').textContent = this.dataset.flight;

                // Show modal
                modal.style.display = 'block';

                // Mark as read + acknowledged
                fetch(`/notification/mark-read/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        this.closest('.notification-item').classList.remove('unread');
                    }
                })
                .catch(err => console.error(err));
            });
        });

        // Acknowledge notification
        document.querySelectorAll('.btn-ack').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const button = this;

                fetch(`/notification/acknowledge/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        // Replace button with "Accepted" text
                        button.outerHTML = '<span class="ack-status"><i class="fas fa-check-circle"></i> Accepted</span>';
                    }
                })
                .catch(err => console.error(err));
            });
        });

        // Dismiss notification
        document.querySelectorAll('.btn-dismiss').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const item = this.closest('.notification-item');

                fetch(`/notification/dismiss/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update UI or remove item
                        item.style.opacity = '0';
                        setTimeout(() => item.remove(), 300);
                    }
                })
                .catch(err => console.error(err));
            });
        });

        // Close Modal
        modalClose.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', e => { 
            if(e.target === modal) modal.style.display = 'none'; 
        });

        // Mobile toggle
        const menuToggle = document.createElement('div');
        menuToggle.className = 'menu-toggle';
        menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        document.body.appendChild(menuToggle);
        const sidebar = document.querySelector('.sidebar');
        menuToggle.addEventListener('click', () => sidebar.classList.toggle('active'));

        // Mark all as read
        document.querySelector('.mark-all-read')?.addEventListener('click', function() {
            document.querySelectorAll('.notification-item').forEach(item => item.classList.remove('unread'));
            showToast('All notifications marked as read', '#10b981');
        });

        // Clear all
        document.querySelector('.clear-all')?.addEventListener('click', function() {
            if(confirm('Are you sure you want to clear all notifications?')) {
                document.querySelector('.notification-list').innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon"><i class="far fa-bell-slash"></i></div>
                        <div class="empty-text">You have no notifications</div>
                        <p>When you have new notifications, they'll appear here</p>
                    </div>`;
                showToast('All notifications cleared', '#3B82F6');
            }
        });

        // Button click effect
        document.querySelectorAll('.btn-small').forEach(button => {
            button.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => this.style.transform = '', 150);
            });
        });

        // Toast notification function
        function showToast(message, color) {
            const toast = document.createElement('div');
            toast.textContent = message;
            Object.assign(toast.style, { 
                position: 'fixed', 
                bottom: '20px', 
                right: '20px', 
                backgroundColor: color, 
                color: 'white', 
                padding: '12px 24px', 
                borderRadius: '8px', 
                box-shadow: '0 4px 12px rgba(0,0,0,0.15)', 
                zIndex: '1000',
                fontSize: '14px',
                fontWeight: '500'
            });
            document.body.appendChild(toast);
            setTimeout(() => { 
                toast.style.opacity = '0'; 
                toast.style.transition = 'opacity 0.5s'; 
                setTimeout(() => toast.remove(), 500); 
            }, 3000);
        }
    });
    </script>

</body>
@endsection