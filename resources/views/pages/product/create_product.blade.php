@extends('layout.app')
@section('title', 'Product | Aranya')

@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                    <h4>Product</h4>
                </div>
            </div>
        </div>
    </div>
   <create-product :prp_artist="{{$artist}}" :prp_vendor="{{$vendor}}" :prp_brand="{{$brand}}" :prp_care="{{$care}}"
     :prp_consignment="{{$consignment}}" :prp_designer="{{$designer}}" :prp_embellish="{{$embellish}}"
     :prp_fabric="{{$fabric}}" :prp_fit="{{$fit}}" :prp_ingredient="{{$ingredient}}" :prp_making="{{$making}}"
     :prp_season="{{$season}}" :prp_colour="{{$colour}}" :prp_size="{{$size}}" :prp_variety="{{$variety}}" :flat_colour="{{ $flat_colour }}" :prp_tax="{{$tax}}"
    />
</div>

<!-- end modal -->
@endsection

@push('script')
    <script src="{{ asset('js/product.js')}}"></script>
@endpush
