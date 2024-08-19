<h1>{{$vacation->balance->employee->fname}} - Vacation Request</h1>

<p>Employee {{ $vacation->balance->employee->full_name }} has requested a vacation:</p>

<ul>
    <li>Start Date: {{ $vacation->start_date }}</li>
    <li>End Date: {{ $vacation->end_date }}</li>
    <li>Duration: {{ $vacation->duration }} days</li>
</ul>

<p>Please review and approve/reject this request.</p>
