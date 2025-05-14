@extends('layout.app')
@section('title', 'Category | Aranya')
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 25px 0;">
    <view-category :prp_fabric="{{ getComposition() }}" />
</div>    

@endsection

@push('script')
<script src="{{ asset('js/category.js') }}"></script>
@endpush