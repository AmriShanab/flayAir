@extends('admin.layout')

@section('page-title', 'Add Flight')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h1 class="page-title">Add New Flight</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Flights
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
                <span>Flight Details</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.store.flights') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Flight Number <span class="text-danger">*</span></label>
                        <input type="text" name="flight_number" class="form-control @error('flight_number') is-invalid @enderror" 
                               value="{{ old('flight_number') }}" required>
                        @error('flight_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="arrival" {{ old('type') == 'arrival' ? 'selected' : '' }}>Arrival</option>
                            <option value="departure" {{ old('type') == 'departure' ? 'selected' : '' }}>Departure</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Scheduled Time <span class="text-danger">*</span></label>
                        <input type="time" name="scheduled_time" class="form-control @error('scheduled_time') is-invalid @enderror" 
                               value="{{ old('scheduled_time') }}" required>
                        @error('scheduled_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                               value="{{ old('date') }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Origin</label>
                        <input type="text" name="origin" class="form-control @error('origin') is-invalid @enderror" 
                               value="{{ old('origin') }}">
                        @error('origin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Destination</label>
                        <input type="text" name="destination" class="form-control @error('destination') is-invalid @enderror" 
                               value="{{ old('destination') }}">
                        @error('destination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Airline</label>
                        <input type="text" name="airline" class="form-control @error('airline') is-invalid @enderror" 
                               value="{{ old('airline') }}">
                        @error('airline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select Status</option>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="delayed" {{ old('status') == 'delayed' ? 'selected' : '' }}>Delayed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="departed" {{ old('status') == 'departed' ? 'selected' : '' }}>Departed</option>
                            <option value="arrived" {{ old('status') == 'arrived' ? 'selected' : '' }}>Arrived</option>
                        </select>
                        @error('status')
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
                            <i class="bi bi-plus-circle"></i> Add Flight
                        </button>
                        <a href="{{ route('admin.add.flights') }}" class="btn btn-outline-secondary">
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
</script>
@endsection