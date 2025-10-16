<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
        // إذا أردت التأخير بعد الالتزام بعملية DB
        $this->afterCommit();
    }

    /**
     * Get the delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * رسالة الإيميل.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You have been assigned a new task')
            ->line("You have been assigned to task: {$this->task->title}")
            ->action('View Task', url("/tasks/{$this->task->id}"))
            ->line('Thank you for using our application!');
    }

    /**
     * بيانات تُخزّن في جدول notifications database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'assigned_by' => $this->task->project->creator->name ?? null,
        ];
    }
}
