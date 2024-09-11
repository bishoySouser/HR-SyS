<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Work From Home Approval</title>
</head>
<body>
    <h1>Work From Home Approval</h1>
    <p>Dear HR Team,</p>
    <p>The following work from home request has been approved:</p>
    <ul>
        <li>Employee: {{ $workFromHome->employee->fname }}</li>
        <li>Date: {{ \Carbon\Carbon::parse($workFromHome->day)->format('F j, Y') }}</li>
        <li>Status: {{ $workFromHome->status }}</li>
    </ul>
    <p>Please take the necessary actions to accommodate this request.</p>
    <p>Thank you,</p>
</body>
</html>
