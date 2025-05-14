@extends('layout.app')
@section('title', 'Section Product | Aranya')

@section('content')
<div id="tableCheckbox" class="col-lg-12 col-12 layout-spacing" style="padding: 25px 0;">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                    <h4>{{ $sectionData->section_name }}</h4>
                </div>
            </div>
        </div>
        {{-- {{ dd($sectionData) }} --}}
        @if($sectionData)
        <section-product :section="$sectionData" />

        @endif
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('js/pages.js') }}"></script>
@endpush
