@extends('layout.app')
@section('title', 'Customer | '.env('APP_NAME'))
@push('style')
    <link href="{{ asset('admin-assets/assets/css/components/timeline/custom-timeline.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
<div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                    <h4>Customer</h4>
                </div>                 
            </div>
        </div>
        {{-- <view-customer /> --}}
    </div>
</div>    
<!-- end modal -->
@endsection

@push('script')
<script src="{{ asset('js/order.js') }}"></script>
@endpush
