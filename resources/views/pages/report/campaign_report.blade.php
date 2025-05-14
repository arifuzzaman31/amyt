@extends('layout.app')
@section('title', 'Campaign Report | Aranya')
@push('style')

@endpush
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <campaign-report />
        </div>
    </div>
</div>    
<!-- end modal -->
@endsection

@push('script')
<script src="{{ asset('js/report.js') }}"></script>
@endpush