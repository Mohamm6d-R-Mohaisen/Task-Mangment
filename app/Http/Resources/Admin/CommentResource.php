<?php

namespace App\Http\Resources\Admin;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $operations = view('admin.comments.sub.operations', ['instance' => $this])->render();
        $task_name=Task::findorfail($this->task_id)->name;
        $user_name=User::findorfail($this->user_id)->name;

        return [
            'id' => $this->id,
            'task_name'=>$task_name,
            'user_name'=>$user_name,
            'operations' => $operations,
        ];
    }
}
