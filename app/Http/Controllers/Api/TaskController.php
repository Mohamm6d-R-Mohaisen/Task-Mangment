<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TaskAssignmentService;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskAssignmentService $taskService)
    {
        $this->taskService = $taskService;

        $this->middleware('permission:view_tasks', ['only' => ['index','show']]);
        $this->middleware('permission:add_tasks', ['only' => ['store']]);
        $this->middleware('permission:edit_tasks', ['only' => ['update']]);
        $this->middleware('permission:delete_tasks', ['only' => ['destroy']]);
    }

   public function index(Request $request, $project_id)
{
    $tasks = Task::with('assignee')
        ->where('project_id', $project_id)
        ->filterByStatus($request->status)   // 👈 فلترة حسب الحالة
        ->searchByTitle($request)           // 👈 بحث حسب العنوان
        ->orderByDesc('id')
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Tasks retrieved successfully',
        'data' => $tasks
    ]);
}


    public function store(Request $request, $project_id)
    {
        $data = array_merge($request->all(), ['project_id' => $project_id]);

        try {
            $task = $this->taskService->assign($data);

            return response()->json([
                'status' => true,
                'message' => 'Task created successfully',
                'data' => $task
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function show($id)
    {
        $task = Task::with(['project','assignee','comments.user'])->find($id);

        if (!$task) {
            return response()->json(['status' => false, 'message' => 'Task not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Task retrieved successfully',
            'data' => $task
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['status' => false, 'message' => 'Task not found'], 404);
        }

        $user = Auth::user();

        if (!$user->can('edit_tasks') && $user->id !== $task->assigned_to) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in-progress,done',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Task updated successfully',
            'data' => $task
        ]);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['status' => false, 'message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['status' => true, 'message' => 'Task deleted successfully']);
    }
}
