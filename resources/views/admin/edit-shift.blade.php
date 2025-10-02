@extends('admin.layout')

@section('content')
<div class="container">
    <h1>Edit Shift</h1>
    <form action="{{ route('admin.update.shift', $shift->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Start Time</label>
            <input type="text" name="start_time" value="{{ $shift->start_time }}" 
                   class="form-control datetimepicker" id="start_time">
        </div>

        <div class="mb-3">
            <label>End Time</label>
            <input type="text" name="end_time" value="{{ $shift->end_time }}" 
                   class="form-control datetimepicker" id="end_time">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="scheduled" {{ $shift->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="in_progress" {{ $shift->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ $shift->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $shift->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

{{-- Flatpickr CSS & JS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const startPicker = flatpickr("#start_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minuteIncrement: 1,
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    endPicker.set("minDate", selectedDates[0]); // ✅ prevent end before start
                }
            }
        });

        const endPicker = flatpickr("#end_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minuteIncrement: 1
        });
    });
</script>
@endsection
