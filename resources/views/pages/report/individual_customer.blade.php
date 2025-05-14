@extends('layout.app')
@section('title', 'Individual Customer Report | Aranya')
@push('style')

@endpush
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <individual-customer-report />
        </div>
    </div>
</div>    
<!-- end modal -->
@endsection

@push('script')
<script src="{{ asset('js/report.js') }}"></script>
@endpush