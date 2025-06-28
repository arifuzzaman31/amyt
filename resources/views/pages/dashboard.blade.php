@extends('layout.app')

@section('content')
<div class="row layout-top-spacing">
    <div class="col-12 layout-spacing">
        <view-dashboard />
    </div> 
</div>
@endsection
@push('script')
@vite(['resources/js/app.js'])
@endpush