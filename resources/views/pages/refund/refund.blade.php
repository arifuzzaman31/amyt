@extends('layout.app')
@section('title', 'Refund | Aranya')

@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            @if(request()->route()->getName() == 'refund')
                <view-refund :pagefrom="'request-refund'" :pagetitle="'Refund Request'" />
                @endif
            @if(request()->route()->getName() == 'approve-refund')
                <view-refund :pagefrom="'approve-refund'" :pagetitle="'Approved Request'" />
            @endif
            @if(request()->route()->getName() == 'reject-refund')
                <view-refund :pagefrom="'reject-refund'" :pagetitle="'Reject Request'" />
            @endif
        </div>
    </div>
</div>    
<!-- end modal -->
@endsection

@push('script')
<script src="{{ asset('js/refund.js') }}"></script>
@endpush