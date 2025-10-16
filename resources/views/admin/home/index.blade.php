@extends('admin.layouts.master')
@section('title', __('Dashboard'))

@section('content')
<div class="page-content-header mb-5">
    <h2 class="table-title">{{ __('Dashboard') }}</h2>
    <p class="text-muted">{{ __('Overview') }}</p>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif


@endsection

@push('scripts')
<!-- Charts & Maps Scripts - فقط لصفحة Dashboard -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="{{ asset('admin/vendors/apexcharts/dist/apexcharts.min.js') }}"></script>

@endpush
