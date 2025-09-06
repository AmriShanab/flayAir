@extends('admin.layout')

@section('content')
<h2>{{ isset($worker) ? 'Edit' : 'Add' }} Worker</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ isset($worker) ? route('workers.update', $worker->id) : route('workers.store') }}" method="POST" class="row g-3">
    @csrf
    @if(isset($worker))
        @method('PUT')
    @endif

    <div class="col-md-6">
        <label class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $worker->first_name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $worker->last_name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $worker->email ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $worker->phone ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Position</label>
        <input type="text" name="position" class="form-control" value="{{ old('position', $worker->position ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active" {{ (old('status', $worker->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $worker->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-success">{{ isset($worker) ? 'Update' : 'Add' }} Worker</button>
    </div>
</form>
@endsection
