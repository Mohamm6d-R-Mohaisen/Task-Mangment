<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\SaveImageTrait;
use App\Traits\HasImages;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    use SaveImageTrait,HasImages;

    // public function __construct()
    // {
    //     $this->middleware('permission:view_abouts|add_abouts', ['only' => ['index','store']]);
    //     $this->middleware('permission:add_abouts', ['only' => ['create','store']]);
    //     $this->middleware('permission:edit_abouts', ['only' => ['edit','update']]);
    //     $this->middleware('permission:delete_abouts', ['only' => ['destroy']]);
    // }

    public function index()
    {
        return view('admin.projects.index');
    }

    public function datatable(Request $request)
    {
        $items = Project::query()->orderBy('id', 'DESC');
        return $this->filterDataTable($items, $request);
    }

    public function create()
    {
        return view('admin.projects.create');
    }
public function show($id)
{

}
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();

    $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::guard('admin')->user()->id,
        ]);



            DB::commit();

            return $this->response_api(200, __('admin.form.added_successfully'), '');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->response_api(400, $this->exMessage($e));
        }
    }


    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return view('admin.projects.create', compact('project'));
    }


    public function update(Request $request, $id)
    {
//        dd($request->all());
        try {
            DB::beginTransaction();

            $project = Project::findOrFail($id);
               $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($request->only(['title', 'description', 'start_date', 'end_date']));
            DB::commit();

            return $this->response_api(200, __('admin.form.updated_successfully'), '');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response_api(400, $this->exMessage($e));
        }
    }

    public function destroy($id)
    {
//        Project::destroy($id);
//         return $this->response_api(200, __('admin.form.deleted_successfully'), '');
//



        try {
            DB::beginTransaction();
            $project=Project::findorfail($id);

            Project::destroy($id);
            return $this->response_api(200, __('admin.form.deleted_successfully'), '');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->response_api(400, $this->exMessage($e));
        }
    }
}
