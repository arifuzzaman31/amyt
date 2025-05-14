@extends('layout.app')
@section('title', 'Edit Product | Aranya')

@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                    <h4>Product: {{ $product->product_name }}</h4>
                </div>                 
            </div>
        </div>
    </div>
    <edit-product :pr_product="{{ $product }}" :attrs="{{ $attrs }}" />
</div>    

<!-- end modal -->
@endsection

@push('script')
    <script src="{{ asset('js/product.js')}}"></script>
@endpush