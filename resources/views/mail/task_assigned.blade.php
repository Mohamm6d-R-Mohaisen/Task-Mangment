<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Task Assigned</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>
    <p>You have been assigned a new task: <strong>{{ $task->title }}</strong></p>

    @if($task->description)
        <p><em>{{ $task->description }}</em></p>
    @endif

    <p>Due Date: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'Not specified' }}</p>

    <p>
        <a href="{{ url('/tasks/'.$task->id) }}">View Task</a>
    </p>

    <p>Best regards,<br>Project Management System</p>
</body>
</html>
