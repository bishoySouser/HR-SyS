<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Work From Home Status Update</title>
</head>
<body>
    <h1>Work From Home Status Update</h1>
    <p>Dear {{ $workFromHome->employee->name }},</p>
    <p>This email is to inform you that your work from home request has been {{ $workFromHome->status }}.</p>
    <ul>
        <li>Date: {{ \Carbon\Carbon::parse($workFromHome->day)->format('F j, Y') }}</li>
        <li>Status: {{ $workFromHome->status }}</li>
    </ul>
    @if ($workFromHome->status === 'Approved')
        <p>We hope you have a productive day working from home.</p>
    @elseif ($workFromHome->status === 'Cancelled')
        <p>Please let us know if you have any questions or concerns.</p>
    @endif
    <p>Thank you,</p>
</body>
</html>
