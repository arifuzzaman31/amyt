@extends('layout.app')
@section('title', 'Edit Section | Aranya')

@section('content')
<div id="tableCheckbox" class="col-lg-12 col-12 layout-spacing" style="padding: 25px 0;">
    <div class="statbox widget box box-shadow">
        @if(isset($sectionData))
            <section-edit :section_info="{{ $sectionData }}" />
        @endif
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('js/pages.js') }}"></script>
@endpush
