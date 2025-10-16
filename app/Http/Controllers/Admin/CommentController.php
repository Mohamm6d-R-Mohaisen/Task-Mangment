<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\comment;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         return view('admin.comments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function datatable(Request $request)
    {
        $items = Comment::query()->orderBy('id', 'DESC');
        return $this->filterDataTable($items, $request);
    }
    public function create()
    {
        //
        $tasks = Task::all();
        $users = User::all();
           return view('admin.comments.create',compact('users','tasks'));
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
            'body' => 'required|string',
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id'
        ]);


        $comment = Comment::create([
            'body' => $request->body,
            'task_id' => $request->task_id,
            'user_id' => $request->user_id
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
        $comment = comment::findOrFail($id);
        $tasks = Task::all();
        $users = User::all();

        return view('admin.comments.create', compact('comment','users','tasks'));
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
               'body' => 'required|string',
               'task_id' => 'required|exists:tasks,id',
               'user_id' => 'required|exists:users,id'
            ]);

            $comment = comment::findOrFail($id);
            $comment->update($request->only('body'));
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
            $comment = comment::findOrFail($id);
            $comment->delete();
            DB::commit();
            return $this->response_api(200, __('admin.form.deleted_successfully'), '');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->response_api(400, $this->exMessage($e));
        }
    }
}
