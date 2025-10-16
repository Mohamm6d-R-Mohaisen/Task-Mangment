<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTaskAssignedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $task;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Task $task)
    {
        $this->user = $user;
        $this->task = $task;
        $this->afterCommit(); // يضمن التنفيذ بعد حفظ المهمة
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;
        $task = $this->task;

        Mail::send('emails.task_assigned', [
            'user' => $user,
            'task' => $task
        ], function ($message) use ($user, $task) {
            $message->to($user->email, $user->name)
                ->subject("New Task Assigned: {$task->title}");
        });
    }
}
