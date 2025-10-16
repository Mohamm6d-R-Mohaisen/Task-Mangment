<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_comments', ['only' => ['index']]);
        $this->middleware('permission:add_comments', ['only' => ['store']]);
    }

    /**
     * عرض التعليقات لمهمة معينة مع فلتر البحث
     */
    public function index(Request $request, $task_id)
    {
        $task = Task::find($task_id);
        if (!$task) {
            return response()->json(['status' => false, 'message' => 'Task not found'], 404);
        }

        $comments = Comment::with('user')
            ->where('task_id', $task_id)
            ->search($request)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Comments retrieved successfully',
            'data' => $comments
        ]);
    }

    /**
     * إنشاء تعليق جديد
     */
    public function store(Request $request, $task_id)
    {
        $validated = $request->validate([
            'body' => 'required|string'
        ]);

        $task = Task::find($task_id);
        if (!$task) {
            return response()->json(['status' => false, 'message' => 'Task not found'], 404);
        }

        $comment = Comment::create([
            'body' => $validated['body'],
            'task_id' => $task_id,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment added successfully',
            'data' => $comment->load('user')
        ], 201);
    }
}
