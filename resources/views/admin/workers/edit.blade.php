@extends('admin.layout')

@section('page-title', 'Edit Worker')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            {{-- <h1 class="page-title">Edit Worker</h1> --}}
            <a href="{{ route('workers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Workers
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <span>Edit Worker Details</span>
            </div>
            <div class="card-body">
                <form action="{{ route('workers.update', $worker->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" 
                                   value="{{ old('first_name', $worker->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" 
                                   value="{{ old('last_name', $worker->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email', $worker->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" 
                                   value="{{ old('phone', $worker->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" 
                                   value="{{ old('position', $worker->position) }}" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', $worker->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $worker->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes', $worker->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Worker
                        </button>
                        
                        <a href="{{ route('workers.index') }}" class="btn btn-outline-secondary">
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
    
    .text-danger {
        color: var(--danger) !important;
    }
    
    .invalid-feedback {
        display: block;
        margin-top: 0.25rem;
        color: var(--danger);
    }
</style>
@endsection