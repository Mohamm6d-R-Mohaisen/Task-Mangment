@extends('admin.layouts.master')
@section('title', isset($task) ? __('admin.global.edit') : __('Add'))
@section('content')

    <form id="kt_form" class="form row d-flex flex-column flex-lg-row addForm"
          data-kt-redirect="{{ route('admin.tasks.index') }}"
          action="{{ isset($task) ? route('admin.tasks.update', $task->id) : route('admin.tasks.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @isset($task)
            @method('PATCH')
        @endisset



        <!-- Main Form -->
        <div class="d-flex flex-column flex-row-fluid gap-3 col-lg-8">
            <div class="card card-flush generalDataTap">
                <div class="salesTitle">
                    <h3> Details</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-5">

                        <label class="required form-label">Title</label>
                        <input type="text" name="title"class="form-control" required
                               value="{{ old('title', $task->title ?? '') }}">
                    </div>



                    <div class="mb-5">
                        <label class="required form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control"
                                  required>{{ old('description', $task->description ?? '') }}</textarea>
                    </div>
                                <div class="mb-5">

                        <label class="required form-label">Due Dte</label>
                        <input type="date" name="due_date"class="form-control" required
                               value="{{ old('due_date', $task->due_date ?? '') }}">
                    </div>



</div>
                            <div class="mb-5">
                        <label class="required form-label">status</label>
                      <select name="status" class="form-select">
                            <option value=""></option>
                            <option value="pending"
                                @isset($task->status){{ $task->status == 'pending' ? 'selected' : '' }}@endisset>
                                pending
                            </option>
                            <option value="in-progress"
                                @isset($task->status){{ $task->status == 'in-progress' ? 'selected' : '' }}@endisset>
                                in-progress
                            </option>
                            <option value="done"
                                @isset($task->status){{ $task->status == 'done' ? 'selected' : '' }}@endisset>
                                done
                            </option>
                        </select>
                                            </div>

                            <div class="mb-5">
                        <label class="required form-label">Project</label>
                        <select name="project_id" class="form-select">
                            <option value=""></option>

                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    @isset($task->project_id)
                                        {{ $task->project_id == $project->id ? 'selected' : '' }}
                                    @endisset>
                                    {{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                            <div class="mb-5">
                        <label class="required form-label">Asggien To</label>
                       <select name="assigned_to" class="form-select">
                            <option value=""></option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    @isset($task->assigned_to)
                                        {{ $task->assigned_to == $user->id ? 'selected' : '' }}
                                    @endisset>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <div class="page-buttuns mt-5">
            <div class="row justify-content-between">
                <div class="d-flex justify-content-end right">
                    <button type="submit" id="kt_submit" class="btn btn-primary me-5">
                        <span class="indicator-label">Save</span>

                    </button>
                    <a href="{{ route('admin.tasks.index') }}" id="kt_ecommerce_add_product_cancel"
                       class="btn btn-light me-5 cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>


@endsection

@push('scripts')
    <script src="{{ asset('admin/plugins/handleSubmitForm.js') }}"></script>


@endpush
