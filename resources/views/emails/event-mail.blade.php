<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .content h1 {
            color: #333333;
        }
        .content p {
            line-height: 1.6;
            color: #666666;
        }
        .event-details {
            margin-top: 20px;
        }
        .event-details h2 {
            color: #333333;
        }
        .event-details p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>You're Invited!</h1>
        </div>
        <div class="content">
            <h1>{{$event->subject}}</h1>
            <p>{{$event->desc}}</p>
            <div class="event-details">
                <h2>Event Details</h2>
                <p><strong>Date:</strong> {{$event->date}}</p>
                <p><strong>Subject:</strong> {{$event->subject}}</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{now()->format('Y')}} Your BVD. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
