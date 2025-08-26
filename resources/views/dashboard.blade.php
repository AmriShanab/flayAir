@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Welcome, {{ auth()->user()->name }} 🎉</h1>
    <p>You are logged in successfully.</p>

    <a href="{{ route('shifts.index') }}">Go to Shifts</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>
@endsection
