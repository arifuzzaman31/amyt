@extends('layout.app')
@section('title', 'Community | Aranya')
@section('content')
<div id="tableHover" class="layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <story-community />
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('js/stories.js') }}"></script>
@endpush
