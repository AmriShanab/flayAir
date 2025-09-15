@extends('admin.layout')

@section('page-title', 'Add Shift')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h1 class="page-title">Add New Shift</h1>
            <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Shifts
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close">&times;</button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <span>Shift Details</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.store.shifts') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Staffs <span class="text-danger">*</span></label>
                        <select name="worker_id" class="form-select @error('worker_id') is-invalid @enderror" required>
                            <option value="">Select Staffs</option>
                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}" {{ old('worker_id') == $worker->id ? 'selected' : '' }}>
                                    {{ $worker->first_name }} {{ $worker->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('worker_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="col-md-6 mb-3">
                        <label class="form-label">Shift Type <span class="text-danger">*</span></label>
                        <select name="shift_type" class="form-select @error('shift_type') is-invalid @enderror" required>
                            <option value="">Select Shift Type</option>
                            <option value="morning" {{ old('shift_type') == 'morning' ? 'selected' : '' }}>Morning</option>
                            <option value="afternoon" {{ old('shift_type') == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                            <option value="evening" {{ old('shift_type') == 'evening' ? 'selected' : '' }}>Evening</option>
                            <option value="night" {{ old('shift_type') == 'night' ? 'selected' : '' }}>Night</option>
                        </select>
                        @error('shift_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                               value="{{ old('start_time') }}" required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                               value="{{ old('end_time') }}" required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select Status</option>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Select Flight</label>
                        <select name="flight_id" class="form-control @error('flight_id') is-invalid @enderror">
                            <option value="">-- Select Flight --</option>
                            @foreach($flights as $flight)
                              <option value="{{ $flight->id }}" {{ old('flight_id') == $flight->id ? 'selected' : '' }}>
                                        {{ $flight->flight_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('flight_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                  


                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Shift
                        </button>
                        <a href="{{ route('shifts.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label {
        font-weight: 500;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid var(--border);
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
    }
    
    .btn {
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background: var(--primary);
        border: none;
    }
    
    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }
    
    .btn-outline-secondary {
        border: 1px solid var(--border);
        color: var(--mid-text);
    }
    
    .btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: var(--border);
    }
    
    .text-danger {
        color: var(--danger) !important;
    }
    
    .invalid-feedback {
        display: block;
        margin-top: 0.25rem;
        color: var(--danger);
    }
    
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
</style>

<script>
    // Close alert functionality
    document.querySelectorAll('.btn-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.alert').style.display = 'none';
        });
    });

    // Set default datetime values to current time
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const timezoneOffset = now.getTimezoneOffset() * 60000; // offset in milliseconds
        const localISOTime = new Date(now - timezoneOffset).toISOString().slice(0, 16);
        
        // Set default values if no previous input
        if (!document.querySelector('input[name="start_time"]').value) {
            document.querySelector('input[name="start_time"]').value = localISOTime;
        }
        
        if (!document.querySelector('input[name="end_time"]').value) {
            const endTime = new Date(now.getTime() + 8 * 60 * 60000); // 8 hours later
            const endISOTime = new Date(endTime - timezoneOffset).toISOString().slice(0, 16);
            document.querySelector('input[name="end_time"]').value = endISOTime;
        }
    });
</script>
@endsection