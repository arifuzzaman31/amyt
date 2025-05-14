@extends('layout.app')
@section('title', 'Home Page | Aranya')
@push('style')
<link href="{{ asset('admin-assets/assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <view-homepage />
        </div>
    </div>
</div>
<!-- end modal -->
@endsection

@push('script')
<script src="{{ asset('js/pages.js') }}"></script>
@endpush
