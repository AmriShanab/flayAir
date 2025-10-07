@extends('admin.layout')

@section('page-title', 'Staff Management')
@section('hide-footer', true)

@section('content')
<head>
    <style>
        .sts-clr {
            color: black
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-container form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .search-container input[type="text"] {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 220px;
        }

        .search-container button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<!-- Page Header -->
<div class="page-header">
    <a href="{{ route('workers.create') }}" class="add-btn">
        <i class="bi bi-plus-circle"></i> Add Staff
    </a>
</div>

<!-- Search Bar -->
<div class="search-container">
    <form method="GET" action="{{ route('workers.index') }}">
        <input type="text" name="search" placeholder="Search staff..." value="{{ request('search') }}">
        <button type="submit"><i class="bi bi-search"></i> Search</button>
    </form>

    @if(request('search'))
        <a href="{{ route('workers.index') }}" style="color: #6c757d; text-decoration:none;">
            <i class="bi bi-x-circle"></i> Clear Search
        </a>
    @endif
</div>

<!-- Workers Table -->
<div class="card">
    <div class="card-header">
        <span>Staff List</span>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        {{-- <th>ID</th> --}}
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
                        {{-- <td data-label="ID">{{ $worker->id }}</td> --}}
                        <td data-label="Name">{{ $worker->first_name }} {{ $worker->last_name }}</td>
                        <td data-label="Email">{{ $worker->email }}</td>
                        <td data-label="Phone">{{ $worker->phone ?? '-' }}</td>
                        <td data-label="Position">{{ $worker->position ?? '-' }}</td>
                        <td data-label="Status">
                            <span class="badge sts-clr" style="background-color: {{ $worker->status === 'active' ? '#28a745' : '#dc3545' }}; color: white;">
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
                                <h3>No staffs found</h3>
                                <p>Get started by adding your first worker</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
