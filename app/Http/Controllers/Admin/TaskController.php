<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         return view('admin.tasks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function datatable(Request $request)
    {
        $items = Task::query()->orderBy('id', 'DESC');
        return $this->filterDataTable($items, $request);
    }
    public function create()
    {
        //
        $projects = Project::all();
        $users = User::all();
           return view('admin.tasks.create',compact('users','projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         try {
            DB::beginTransaction();
          $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in-progress,done',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
           'project_id' => 'required|exists:projects,id'
        ]);



        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to
        ]);
           DB::commit();

            return $this->response_api(200, __('admin.form.added_successfully'), '');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->response_api(400, $this->exMessage($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $task = Task::findOrFail($id);
        $projects = Project::all();
        $users = User::all();

        return view('admin.tasks.create', compact('task','users','projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            DB::beginTransaction();
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'in:pending,in-progress,done',
                'due_date' => 'nullable|date',
                'assigned_to' => 'nullable|exists:users,id',
                'project_id' => 'required|exists:projects,id'
            ]);

            $task = Task::findOrFail($id);
            $task->update($request->all());
            DB::commit();

            return $this->response_api(200, __('admin.form.updated_successfully'), '');
         } catch (\Exception $e) {
            DB::rollBack();
            return $this->response_api(400, $this->exMessage($e));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            DB::beginTransaction();
            $task = Task::findOrFail($id);
            $task->delete();
            DB::commit();
            return $this->response_api(200, __('admin.form.deleted_successfully'), '');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->response_api(400, $this->exMessage($e));
        }
    }
}
