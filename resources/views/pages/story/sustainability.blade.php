@extends('layout.app')
@section('title', 'Sustainability | Aranya')
@section('content')
<div id="tableHover" class="layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <view-sustainability />
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('js/stories.js') }}"></script>
@endpush
