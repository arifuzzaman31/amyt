@extends('layout.app')
@section('title', 'Campaign | Aranya')

@push('style')
<link href="{{ asset('admin-assets/plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')

<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                    <h4>Campaign</h4>
                </div>                 
            </div>
        </div>
    </div>
    <create-campaign />
</div> 
   
@endsection

@push('script')
<script src="{{ asset('admin-assets/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<script src="{{ asset('js/campaign.js') }}"></script>
@endpush