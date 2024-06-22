<h1>Vacation Request Rejected</h1>

<p>Dear {{ $vacation->leaveBalance->employee->name }},</p>

<p>Your vacation request for the period {{ $vacation->start_date }} to {{ $vacation->end_date }} has been rejected by {{ $rejectedBy }}.</p>

<p>If you have any questions, please contact the HR department.</p>

<p>Thank you,<br>HR Team</p>