@extends('admin.layout')

@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h1 class="page-title">User Management</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
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
</script>
@endsection