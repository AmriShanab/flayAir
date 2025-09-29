@extends('admin.layout')

@section('page-title', 'Shifts Management')

@section('content')
<head>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<div class="page-header">
    {{-- <h1 class="page-title">Shifts Management</h1> --}}
    <a href="{{ route('admin.add.shifts') }}" class="add-btn">
        <i class="bi bi-plus-circle"></i> Add New Shift
    </a>
</div>

<div class="card">
    <div class="card-header">
        <span><i class="bi bi-clock-history"></i> All Shifts</span>
        <div class="d-flex align-items-center">
            <input type="text" class="form-control form-control-sm me-2" placeholder="Search shifts..." id="searchInput" style="max-width: 200px;">
            <button class="btn btn-sm btn-light" id="filterBtn">
                <i class="bi bi-filter"></i> Filter
            </button>
        </div>
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
                <tbody>
                    @forelse($shifts as $shift)
                        <tr>
                            {{-- @dd($shift->worker->first_name) --}}
                            <td>{{ $shift->id }}</td>
                            <td>{{ $shift->worker->first_name ?? 'N/A' }}</td>
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
                                    <a href="#" class="btn-action btn-edit"><i class="bi bi-pencil"></i></a>
                                    <form action="#" method="POST" style="display:inline;">
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
        
        <div class="empty-state" style="display: none;">
            <i class="bi bi-calendar-x"></i>
            <h4>No Shifts Found</h4>
            <p>There are no shifts matching your criteria.</p>
            <a href="{{ route('admin.add.shifts') }}" class="add-btn mt-3">
                <i class="bi bi-plus-circle"></i> Create New Shift
            </a>
        </div>
    </div>

    <div class="card-footer">
    {{ $shifts->links('pagination::bootstrap-5') }}
</div>

</div>

  <!-- Bootstrap 5 JS Bundle CDN (optional, for JS components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
