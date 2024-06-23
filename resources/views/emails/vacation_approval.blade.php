<h1>Vacation Request Approved</h1>

<p>Dear {{ $vacation->balance->employee->fname }},</p>

<p>Your vacation request for the period {{ $vacation->start_date }} to {{ $vacation->end_date }} has been approved by HR.</p>

<p>Duration: {{ $vacation->duration }} day(s)</p>

<p>Your remaining vacation days: {{ $remainingDays }}</p>

<p>Enjoy your time off!</p>

<p>Thank you,<br>HR Team</p>
