@extends('layout.app')
@section('title', 'Service | '.env('APP_NAME'))

@section('content')
    <div class="row">                
        <service />
    </div>
<!-- end modal -->
@endsection

@push('script')
@vite(['resources/js/service.js'])
@endpush
