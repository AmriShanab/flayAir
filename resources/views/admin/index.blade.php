@extends('admin.layout')

@section('page-title', 'Admin Dashboard')

@section('content')
<div class="dashboard-header">
    {{-- <h2>Admin Dashboard</h2> --}}
    {{-- <div class="date-display">
        {{-- <i class="bi bi-calendar"></i> {{ now()->format('l, F j, Y') }} --}}
    {{-- </div> --}}
</div>

<!-- Stats Section -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon workers">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-info">
            {{-- @dd($totalWorkers) --}}
            <h3>{{ $totalWorkers ?? 0 }}</h3>
            <p>Total Workers</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon shifts">
            <i class="bi bi-calendar-week"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalShifts ?? 0 }}</h3>
            <p>Shifts This Week</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon flights">
            <i class="bi bi-airplane"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalFlights ?? 0 }}</h3>
            <p>Flights Today</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Workers -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <span>Recent Workers</span>
                <i class="bi bi-people"></i>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentWorkers as $worker)
                            <tr>
                                <td>{{ $worker->first_name }} {{ $worker->last_name }}</td>
                                <td>{{ $worker->position ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $worker->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($worker->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No workers found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('workers.index') }}">View All Workers</a>
            </div>
        </div>
    </div>

    <!-- Recent Shifts -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <span>Recent Shifts</span>
                <i class="bi bi-calendar-week"></i>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Worker</th>
                                <th>Type</th>
                                <th>Start</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentShifts as $shift)
                            <tr>
                                <td>{{ $shift->worker->first_name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($shift->shift_type) }}</td>
                                <td>{{ date('M j, H:i', strtotime($shift->start_time)) }}</td>
                                <td>
                                    <span class="badge 
                                        @if($shift->status == 'completed') bg-success
                                        @elseif($shift->status == 'in_progress') bg-warning
                                        @elseif($shift->status == 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($shift->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No recent shifts found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.add.shifts') }}">Add New Shift</a>
            </div>
        </div>
    </div>

    <!-- Recent Flights -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <span>Recent Flights</span>
                <i class="bi bi-airplane"></i>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Flight No</th>
                                <th>Route</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentFlights as $flight)
                            <tr>
                                <td>{{ strtoupper($flight->flight_number) }}</td>
                                <td>{{ $flight->origin ?? '-' }} → {{ $flight->destination ?? '-' }}</td>
                                <td>{{ $flight->scheduled_time }}</td>
                                <td>
                                    <span class="badge 
                                        @if($flight->status == 'scheduled') bg-secondary
                                        @elseif($flight->status == 'on_time') bg-success
                                        @elseif($flight->status == 'delayed') bg-warning
                                        @elseif($flight->status == 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($flight->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No recent flights found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.add.flights') }}">Add New Flight</a>
            </div>
        </div>
    </div>
</div>

<style>
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
        padding: 0;
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
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .table th, .table td {
            padding: 0.5rem;
        }
    }
</style>
@endsection