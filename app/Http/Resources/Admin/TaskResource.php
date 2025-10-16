<?php

namespace App\Http\Resources\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $operations = view('admin.tasks.sub.operations', ['instance' => $this])->render();
        $project_name=Project::findorfail($this->project_id)->name;
        $user_name=User::findorfail($this->assigned_to)->name;

        return [
            'id' => $this->id,
            'title'=>$this->title,
            'status'=>$this->status,
            'project_name'=>$project_name,
            'user_name'=>$user_name,

            'operations' => $operations,
        ];
    }
}
