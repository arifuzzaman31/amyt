@extends('layout.app')
@section('title', 'Create Purchase | '.env('APP_NAME'))

@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox">
        <div class="widget-header">
            <create-service />
        </div>
    </div>
</div>    
<!-- end modal -->
@endsection
@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/users/account-setting.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/dropify/dropify.min.css')}}">
@endpush
@push('script')
@vite(['resources/js/service.js'])
<script src="{{ asset('admin-assets/plugins/dropify/dropify.min.js')}}"></script>
<script src="{{ asset('admin-assets/assets/js/users/account-settings.js')}}"></script>
@endpush