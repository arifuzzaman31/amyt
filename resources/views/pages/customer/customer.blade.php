@extends('layout.app')
@section('title', 'Customer | '.env('APP_NAME'))
@push('style')
    <link href="{{ asset('admin-assets/assets/css/components/timeline/custom-timeline.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="row">                
        <view-customer />
    </div>
<!-- end modal -->
@endsection

@push('script')
@vite(['resources/js/customer.js'])
@endpush
