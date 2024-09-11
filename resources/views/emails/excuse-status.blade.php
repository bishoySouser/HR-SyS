<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Excuse Status Update</title>
</head>
<body>
    <h1>Excuse Status Update</h1>
    <p>Dear {{ $excuse->employee->fname }},</p>
    <p>This email is to inform you that your Excuse request has been {{ $excuse->status }}.</p>
    <ul>
        <li>Date: {{ \Carbon\Carbon::parse($excuse->day)->format('F j, Y') }}</li>
        <li>Status: {{ $excuse->status }}</li>
    </ul>
    @if ($excuse->status === 'Approved')
        <p>We hope you have a productive day excuse.</p>
    @elseif ($excuse->status === 'Cancelled')
        <p>Please let us know if you have any questions or concerns.</p>
    @endif
    <p>Thank you,</p>
</body>
</html>
