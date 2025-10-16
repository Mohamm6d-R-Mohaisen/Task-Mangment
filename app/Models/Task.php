<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Admin\TaskResource;
use App\Traits\CommonQueryScopes;

class Task extends Model
{
    //
    use HasFactory,CommonQueryScopes;
    public $resource = TaskResource::class;
    protected $fillable = [
            'title',
            'description' ,
            'status' ,
            'due_date',
            'assigned_to',
           'project_id'

    ];
    public function scopeSearch($query, $request)
    {
        if (!empty($request->search['value'])) {
            $search = '%' . $request->search['value'] . '%';
            return $query->where(function($r) use ($search){
                    $r->where('title', 'LIKE', $search);

            });
        }
        return $query;
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
