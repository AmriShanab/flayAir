@extends('admin.layout')

@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h1 class="page-title">User Management</h1>
            <div class="header-actions">
                <button class="btn btn-warning my-5" id="create-user-btn">
                    <i class="bi bi-person-plus"></i> Create New User
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    
        @if(session('success'))
            <div class="alert alert-success">
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close">&times;</button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <span>System Users</span>
                <span class="badge bg-primary">{{ $users->count() }} Users</span>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td data-label="ID">{{ $user->id }}</td>
                                <td data-label="Name">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td data-label="Email">{{ $user->email }}</td>
                                <td data-label="Role">
                                    <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td data-label="Status">
                                    <span class="badge {{ $user->is_locked ? 'bg-danger' : 'bg-success' }}">
                                        {{ $user->is_locked ? 'Blocked' : 'Active' }}
                                    </span>
                                </td>
                                <td data-label="Registered">{{ $user->created_at->format('M j, Y') }}</td>
                                <td data-label="Actions" class="text-center">
                                    <div class="action-buttons">
                                        @if($user->is_locked)
                                            <form action="{{ route('admin.unblock', $user->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn-action btn-success" 
                                                        title="Unblock User" 
                                                        onclick="return confirm('Are you sure you want to unblock this user?')">
                                                    <i class="bi bi-unlock"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">No action needed</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="bi bi-people"></i>
                                        <h3>No users found</h3>
                                        <p>There are no users in the system yet.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
                        <a href="http://endevodigital.com/" style="text-decoration: none; color:grey; font-size:1rem; text-align:center; margin-top:20rem;">Powered by EndevoDigital</a>

</div>

<!-- Create User Modal -->
<div id="create-user-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Create New User</h3>
            <button type="button" class="modal-close" id="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            {{-- {{ route('admin.users.store') }} --}}
            <form id="create-user-form" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" class="form-control" required 
                           placeholder="Enter user's full name">
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control" required 
                           placeholder="Enter user's email address">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" class="form-control" required 
                           placeholder="Enter password" minlength="8">
                    <div class="password-requirements">
                        <small>Password must be at least 8 characters long</small>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="form-control" required placeholder="Confirm password">
                </div>

                <!-- Hidden role field set to 'user' -->
                <input type="hidden" name="role" value="user">

                <div class="form-group">
                    <label>Role</label>
                    <div class="role-display">
                        <span class="badge bg-info">User</span>
                        <small class="text-muted">New users are automatically assigned the 'user' role</small>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" id="cancel-create">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Create User
                    </button>
                </div>
            </form>
                                        <a href="http://endevodigital.com/" style="text-decoration: none; color:grey; font-size:1rem; text-align:center; padding-top:21rem;">Powered by EndevoDigital</a>

        </div>

    </div>

</div>

<style>
    .avatar-sm {
        width: 2rem;
        height: 2rem;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        min-height: 2.5rem;
        align-items: center;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        border: none;
    }
    
    .btn-success {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .btn-success:hover {
        background-color: #a7f3d0;
        transform: scale(1.05);
    }
    
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
    
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .bg-primary {
        background-color: rgba(79, 70, 229, 0.1) !important;
        color: var(--primary) !important;
    }
    
    .bg-success {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
    }
    
    .bg-danger {
        background-color: #fee2e2 !important;
        color: #b91c1c !important;
    }
    
    .bg-info {
        background-color: #dbeafe !important;
        color: #1e40af !important;
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-left: 4px solid var(--success);
    }
    
    .alert-danger {
        background-color: #fee2e2;
        color: #b91c1c;
        border-left: 4px solid var(--danger);
    }
    
    .alert .btn-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: inherit;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        border-radius: 0.5rem;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        color: #1f2937;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
        padding: 0;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.375rem;
    }

    .modal-close:hover {
        background-color: #f3f4f6;
        color: #374151;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .error-message {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    .password-requirements {
        margin-top: 0.25rem;
    }

    .password-requirements small {
        color: #6b7280;
        font-size: 0.75rem;
    }

    .role-display {
        padding: 0.75rem;
        background-color: #f9fafb;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
    }

    .role-display small {
        display: block;
        margin-top: 0.25rem;
        color: #6b7280;
    }

    .modal-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    .btn-warning {
        background-color: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background-color: #d97706;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
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

        .header-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .modal-content {
            width: 95%;
            margin: 1rem;
        }

        .modal-actions {
            flex-direction: column;
        }

        .modal-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

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

    // Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('create-user-modal');
        const createBtn = document.getElementById('create-user-btn');
        const closeBtn = document.getElementById('close-modal');
        const cancelBtn = document.getElementById('cancel-create');
        const form = document.getElementById('create-user-form');

        // Open modal
        createBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });

        // Close modal
        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
            form.reset(); // Reset form when closing
        }

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            // Check if passwords match
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please check and try again.');
                return;
            }

            // Check password length
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                return;
            }
        });
    });
</script>
@endsection