<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $operations = view('admin.projects.sub.operations', ['instance' => $this])->render();

        return [
            'id' => $this->id,
            'title'=>$this->title,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'operations' => $operations,
        ];
    }
}
