@extends('layout.app')
@section('title', 'Customer | '.env('APP_NAME'))

@section('content')
    <div class="row">                
        <view-customer />
    </div>
<!-- end modal -->
@endsection

@push('script')
@vite(['resources/js/customer.js'])
@endpush
