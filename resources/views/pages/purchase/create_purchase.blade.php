@extends('layout.app')
@section('title', 'Create Purchase | '.env('APP_NAME'))

@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <create-purchase />
        </div>
    </div>
</div>    
<!-- end modal -->
@endsection

@push('script')
@vite(['resources/js/supplier.js'])
@endpush