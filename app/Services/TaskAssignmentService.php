<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Jobs\SendTaskAssignedEmailJob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TaskAssignmentService
{
    public function assign(array $data): Task
    {
        $validator = Validator::make($data, [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in-progress,done',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $task = Task::create([
            'project_id' => $data['project_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'due_date' => $data['due_date'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);

        // إذا تم تعيين المستخدم
        if (!empty($data['assigned_to'])) {
            $user = User::find($data['assigned_to']);
            if ($user) {
                // أرسل الإيميل عبر الـ Job (Queue)
                dispatch(new SendTaskAssignedEmailJob($user, $task));
            }
        }

        return $task;
    }
}
