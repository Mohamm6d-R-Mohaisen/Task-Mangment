<?php

namespace App\Models;

use App\Http\Resources\Admin\CommentResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public $resource = CommentResource::class;
    protected $table = 'comments';
    protected $fillable = [
               'body',
               'task_id' ,
               'user_id'
    ];

    public function scopeSearch($query, $request)
    {
        if (!empty($request->search['value'])) {
            $search = '%' . $request->search['value'] . '%';
            return $query->where('subject', 'like', $search);
        }
        return $query;

    }
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
