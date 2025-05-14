@extends('layout.app')
@section('title', 'Edit Category | Aranya')
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 25px 0;">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                    <h4>Edit {{ $categorydata->category_name }}</h4>
                </div>                 
            </div>
        </div>
            <update-category :categorydata="{{ $categorydata }}" />
    </div>
</div>    

@endsection

@push('script')
<script src="{{ asset('js/category.js') }}"></script>
@endpush