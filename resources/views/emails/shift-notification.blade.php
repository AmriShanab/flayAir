<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shift Notification</title>
</head>
<body>
    <p>Hi {{ $shift->worker->first_name }},</p>

    <p>{{ $messageText }}</p>

    <p><strong>Shift Details:</strong></p>
    <ul>
        <li>Start Time: {{ $shift->start_time->format('Y-m-d H:i') }}</li>
        <li>End Time: {{ $shift->end_time->format('Y-m-d H:i') }}</li>
        @if($shift->flight)
            <li>Flight: {{ $shift->flight->flight_number }} ({{ $shift->flight->origin ?? '-' }} → {{ $shift->flight->destination ?? '-' }})</li>
        @endif
    </ul>

    <p>Thank you,<br>Shift Management Team</p>
</body>
</html>
