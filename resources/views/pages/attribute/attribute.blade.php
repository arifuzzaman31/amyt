@extends('layout.app')
@section('title', 'Attribute | '.config('app.name'))
@push('style')
<link href="{{ asset('admin-assets/plugins/animate/animate.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/forms/switches.css')}}">
@endpush
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    {{-- <div class="statbox widget box-shadow">
        <div class="widget-header"> --}}
            <view-attribute />
        {{-- </div>
    </div> --}}
</div>
@endsection

@push('script')
@vite(['resources/js/app.js'])
@endpush