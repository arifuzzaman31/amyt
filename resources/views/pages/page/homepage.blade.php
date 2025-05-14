@extends('layout.app')
@section('title', 'Home Page | Aranya')
@push('style')
<link href="{{ asset('admin-assets/assets/css/components/tabs-accordian/custom-tabs.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <h4 class="mx-2">Section Page</h4>
                        <div class="text-center m-2">
                            <a type="button" href="{{ route('page.create') }}" class="btn btn-primary" >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder-plus"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg> Add New
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <view-page />
        </div>
    </div>
</div>
<!-- end modal -->
@endsection

@push('script')
<script src="{{ asset('js/pages.js') }}"></script>
@endpush

