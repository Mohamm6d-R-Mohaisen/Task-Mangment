@extends('admin.layouts.master')
@section('title', isset($project) ? __('admin.global.edit_about') : __('admin.global.add_new_about'))
@section('content')

    <form id="kt_form" class="form row d-flex flex-column flex-lg-row addForm"
          data-kt-redirect="{{ route('admin.projects.index') }}"
          action="{{ isset($project) ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @isset($project)
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
                               value="{{ old('title', $project->title ?? '') }}">
                    </div>



                    <div class="mb-5">
                        <label class="required form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control"
                                  required>{{ old('description', $project->description ?? '') }}</textarea>
                    </div>
                                <div class="mb-5">

                        <label class="required form-label">Start At</label>
                        <input type="date" name="start_date"class="form-control" required
                               value="{{ old('start_date', $project->start_date ?? '') }}">
                    </div>
             <div class="mb-5">

                        <label class="required form-label">End At</label>
                        <input type="date" name="end_date"class="form-control" required
                               value="{{ old('end_date', $project->end_date ?? '') }}">
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
                    <a href="{{ route('admin.projects.index') }}" id="kt_ecommerce_add_product_cancel"
                       class="btn btn-light me-5 cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>


@endsection

@push('scripts')
    <script src="{{ asset('admin/plugins/handleSubmitForm.js') }}"></script>


@endpush
