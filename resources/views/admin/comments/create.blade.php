@extends('admin.layouts.master')
@section('title', isset($comment) ? __('admin.global.edit') : __('Add'))
@section('content')

    <form id="kt_form" class="form row d-flex flex-column flex-lg-row addForm"
          data-kt-redirect="{{ route('admin.comments.index') }}"
          action="{{ isset($comment) ? route('admin.comments.update', $comment->id) : route('admin.comments.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @isset($comment)
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
                        <label class="required form-label">Body</label>
                        <textarea name="body" rows="4" class="form-control"
                                  required>{{ old('body', $comment->body ?? '') }}</textarea>
                    </div>

                            <div class="mb-5">
                        <label class="required form-label">Task</label>
                        <select name="task_id" class="form-select">
                            <option value=""></option>

                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}"
                                    @isset($comment->task_id)
                                        {{ $comment->task_id == $task->id ? 'selected' : '' }}
                                    @endisset>
                                    {{ $task->title }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                            <div class="mb-5">
                        <label class="required form-label">Asggien To</label>
                       <select name="user_id" class="form-select">
                            <option value=""></option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    @isset($comment->user_id)
                                        {{ $comment->user_id == $user->id ? 'selected' : '' }}
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
                    <a href="{{ route('admin.comments.index') }}" id="kt_ecommerce_add_product_cancel"
                       class="btn btn-light me-5 cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>


@endsection

@push('scripts')
    <script src="{{ asset('admin/plugins/handleSubmitForm.js') }}"></script>


@endpush
