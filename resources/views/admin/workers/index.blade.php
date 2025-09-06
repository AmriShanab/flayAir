@extends('admin.layout')

@section('page-title', 'Workers Management')

@section('content')

    <!-- Main Content -->
    
    

        <!-- Page Header -->
        <div class="page-header">
            {{-- <h1 class="page-title">Workers</h1> --}}
            <a href="{{ route('workers.create') }}" class="add-btn">
                <i class="bi bi-plus-circle"></i> Add Worker
            </a>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert">
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close">&times;</button>
            </div>
        @endif

        <!-- Workers Table Card -->
        <div class="card">
            <div class="card-header">
                <span>Workers List</span>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workers as $worker)
                            <tr>
                                <td data-label="ID">{{ $worker->id }}</td>
                                <td data-label="Name">{{ $worker->first_name }} {{ $worker->last_name }}</td>
                                <td data-label="Email">{{ $worker->email }}</td>
                                <td data-label="Phone">{{ $worker->phone ?? '-' }}</td>
                                <td data-label="Position">{{ $worker->position ?? '-' }}</td>
                                <td data-label="Status">
                                    <span class="badge {{ $worker->status }}">
                                        {{ ucfirst($worker->status) }}
                                    </span>
                                </td>
                                <td data-label="Actions" class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('workers.edit', $worker->id) }}" class="btn-action btn-edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('workers.destroy', $worker->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="bi bi-people"></i>
                                        <h3>No workers found</h3>
                                        <p>Get started by adding your first worker</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
@if($workers->hasPages())
<div class="card-footer">
    {{ $workers->links('pagination::bootstrap-5') }}
</div>
@endif

@endsection