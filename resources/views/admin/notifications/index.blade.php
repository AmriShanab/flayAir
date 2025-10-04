@extends('admin.layout')

@section('page-title', 'Notifications Management')

@section('content')
<div class="table-container">
    <table class="table my-5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Staff Name</th>
                <th>Staff ID</th>
                <th>Title</th>
                <th>Message</th>
                <th>Status</th>
                {{-- <th>Acknowledged</th> --}}
                {{-- <th>Actions</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($notifications as $notification)
                <tr>
                    <td data-label="ID">{{ $notification->id }}</td>
                    <td data-label="Worker Name">{{ $notification->worker->first_name ?? 'N/A' }}</td>
                    <td data-label="Worker ID">{{ $notification->worker_id }}</td>
                    <td data-label="Title">{{ $notification->title }}</td>
                    <td data-label="Message">{{ $notification->message }}</td>
                    <td data-label="Status">
                        @if($notification->is_read == 1)
                            <span class="badge bg-info">Read</span>
                        @elseif($notification->is_read == 2)
                            <span class="badge bg-success">Accepted</span>
                        @elseif($notification->is_read == 3)
                            <span class="badge bg-danger">Dismissed</span>
                        @else
                        <span class="badge bg-secondary">Unread</span>
                        @endif
                    </td>
                    {{-- <td data-label="Acknowledged">
                        @if($notification->acknowledged)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-warning">No</span>
                        @endif
                    </td> --}}
                    {{-- <td data-label="Actions" class="action-buttons">
                       
                        <form action="{{ route('admin.notifications.acknowledge', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Acknowledge</button>
                        </form>
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- 8 --}}

</div>
                    <a href="http://endevodigital.com/" style="text-decoration: none; color:grey; font-size:1rem; text-align:center; margin-top:35rem !important; margin-left:32rem;">Powered by Endevo Digital</a>

@endsection
