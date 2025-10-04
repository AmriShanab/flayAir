@extends('admin.layout')

@section('page-title', 'Shifts Management')

@section('content')
<head>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .add-btn {
            background-color: #0d6efd;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.15s ease-in-out;
        }
        
        .add-btn:hover {
            background-color: #0b5ed7;
            color: white;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            border: none;
            background: transparent;
            padding: 0.25rem;
            border-radius: 0.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-edit {
            color: #6c757d;
        }
        
        .btn-edit:hover {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .btn-delete {
            color: #6c757d;
        }
        
        .btn-delete:hover {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .filter-container {
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: end;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
        }
    </style>
</head>

<div class="page-header">
    <h1 class="page-title">Shifts Management</h1>
    <a href="{{ route('admin.add.shifts') }}" class="add-btn">
        <i class="bi bi-plus-circle"></i> Add New Shift
    </a>
</div>

<div class="card">
    <div class="card-header">
        <span><i class="bi bi-clock-history"></i> All Shifts</span>
        <div class="d-flex align-items-center">
            <input type="text" class="form-control form-control-sm me-2" placeholder="Search shifts..." id="searchInput" style="max-width: 200px;">
            {{-- <button class="btn btn-sm btn-light" id="toggleFilterBtn">
                <i class="bi bi-filter"></i> Filter
            </button> --}}
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-container" id="filterContainer" style="display: none;">
        <form id="filterForm" method="GET" action="{{ route('admin.view.shifts') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="form-label">Worker</label>
                    <select class="form-select" name="worker_id" id="workerFilter">
                        <option value="">All Workers</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" {{ request('worker_id') == $worker->id ? 'selected' : '' }}>
                                {{ $worker->first_name }} {{ $worker->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="form-label">Date Range</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="start_date" id="startDate" value="{{ request('start_date') }}">
                        <span class="input-group-text">to</span>
                        <input type="date" class="form-control" name="end_date" id="endDate" value="{{ request('end_date') }}">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label class="form-label">Start Time</label>
                    <select class="form-select" name="start_time" id="startTimeFilter">
                        <option value="">Any Time</option>
                        <option value="morning" {{ request('start_time') == 'morning' ? 'selected' : '' }}>Morning (6AM - 12PM)</option>
                        <option value="afternoon" {{ request('start_time') == 'afternoon' ? 'selected' : '' }}>Afternoon (12PM - 6PM)</option>
                        <option value="evening" {{ request('start_time') == 'evening' ? 'selected' : '' }}>Evening (6PM - 12AM)</option>
                        <option value="night" {{ request('start_time') == 'night' ? 'selected' : '' }}>Night (12AM - 6AM)</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFilters">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Worker</th>
                        <th>Date</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Duration</th>
                        <th>Flight No.</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="shiftsTableBody">
                    @forelse($shifts as $shift)
                        <tr>
                            <td>{{ $shift->id }}</td>
                            <td>{{ $shift->worker->first_name ?? 'N/A' }} {{ $shift->worker->last_name ?? '' }}</td>
                            <td>{{ $shift->start_time?->format('Y-m-d') ?? 'N/A' }}</td>
                            <td>{{ $shift->start_time?->format('H:i A') ?? 'N/A' }}</td>
                            <td>{{ $shift->end_time?->format('H:i A') ?? 'N/A' }}</td>
                            <td>{{ $shift->duration }}</td>
                            <td>{{ $shift->flight->flight_number ?? 'N/A' }}</td>
                            <td>
                                @switch($shift->status)
                                    @case('completed')
                                        <span class="badge bg-success text-white">Completed</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-warning text-white">In Progress</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger text-white">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary text-white">Scheduled</span>
                                @endswitch
                            </td>
                        <td>
    <div class="action-buttons">
        {{-- Edit button should go to edit page --}}
        <a href="{{ route('admin.edit.shift', $shift->id) }}" class="btn-action btn-edit">
            <i class="bi bi-pencil"></i>
        </a>

        {{-- Delete button (form with DELETE method) --}}
        <form action="{{ route('admin.delete.shift', $shift->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Are you sure?')">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </div>
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No Shifts Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="empty-state" id="emptyState" style="{{ $shifts->count() > 0 ? 'display: none;' : '' }}">
            <i class="bi bi-calendar-x"></i>
            <h4>No Shifts Found</h4>
            <p>There are no shifts matching your criteria.</p>
            <a href="{{ route('admin.add.shifts') }}" class="add-btn mt-3">
                <i class="bi bi-plus-circle"></i> Create New Shift
            </a>
        </div>
    </div>

    <div class="card-footer">
        {{ $shifts->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

</div>
                    {{-- <a href="http://endevodigital.com/" style="text-decoration: none; color:grey; font-size:1rem; text-align:center; margin-top:500rem; padding-left:31rem;">Powered by EndevoDigital</a> --}}

<!-- Bootstrap 5 JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleFilterBtn = document.getElementById('toggleFilterBtn');
        const filterContainer = document.getElementById('filterContainer');
        const resetFiltersBtn = document.getElementById('resetFilters');
        const searchInput = document.getElementById('searchInput');
        const shiftsTableBody = document.getElementById('shiftsTableBody');
        const emptyState = document.getElementById('emptyState');
        
        // Toggle filter visibility
        toggleFilterBtn.addEventListener('click', function() {
            if (filterContainer.style.display === 'none') {
                filterContainer.style.display = 'block';
            } else {
                filterContainer.style.display = 'none';
            }
        });
        
        // Reset filters
        resetFiltersBtn.addEventListener('click', function() {
            document.getElementById('workerFilter').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('startTimeFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('filterForm').submit();
        });
        
        // Client-side search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = shiftsTableBody.getElementsByTagName('tr');
            let visibleRows = 0;
            
            for (let row of rows) {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            }
            
            // Show/hide empty state based on visible rows
            if (visibleRows === 0 && searchTerm !== '') {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        });
        
        // Auto-submit form when certain filters change
        const autoSubmitFilters = ['workerFilter', 'startTimeFilter', 'statusFilter'];
        autoSubmitFilters.forEach(filterId => {
            document.getElementById(filterId).addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
    });
</script>
@endsection