@extends('layout.app')

@section('content')
<div class="row layout-top-spacing">
    <div class="col-12 layout-spacing">
        <view-dashboard />
    </div> 
</div>
@endsection
@push('script')
<script src="{{ asset('js/app.js') }}"></script>
@endpush