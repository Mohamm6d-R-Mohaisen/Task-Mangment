<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_projects', ['only' => ['index','show']]);
        $this->middleware('permission:add_projects', ['only' => ['store']]);
        $this->middleware('permission:edit_projects', ['only' => ['update']]);
        $this->middleware('permission:delete_projects', ['only' => ['destroy']]);
    }

    /**
     * عرض جميع المشاريع مع فلتر البحث
     */
 public function index(Request $request)
{
    $projects = Project::with('creator')
        ->searchByTitle($request)
        ->orderByDesc('id')
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Projects retrieved successfully',
        'data' => $projects
    ]);
}

    /**
     * عرض مشروع محدد
     */
    public function show($id)
    {
        $project = Project::with('creator', 'tasks')->find($id);

        if (!$project) {
            return response()->json(['status' => false, 'message' => 'Project not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Project retrieved successfully',
            'data' => $project
        ]);
    }

    /**
     * إنشاء مشروع جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::create(array_merge($validated, [
            'created_by' => Auth::id()
        ]));

        Cache::forget('projects_list');

        return response()->json([
            'status' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    /**
     * تحديث مشروع
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['status' => false, 'message' => 'Project not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($validated);
        Cache::forget('projects_list');

        return response()->json([
            'status' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    /**
     * حذف مشروع
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['status' => false, 'message' => 'Project not found'], 404);
        }

        $project->delete();
        Cache::forget('projects_list');

        return response()->json(['status' => true, 'message' => 'Project deleted successfully']);
    }
}
