@extends('layout.app')
@section('title', 'Customer Order | Aranya')

@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
<div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                    <h4>Order of {{ $orders[0] ? $orders[0]->user->name : 'Unknown'}}</h4>
                </div>                 
            </div>
            <div>
        </div>
        
        <div class="widget-content widget-content-area">
            <table class="table table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>OrderID</th>
                        <th>Price</th>
                        <th>Shipping</th>
                        <th>PaymentBy</th>
                        <th class="text-center">Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                     @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->total_price }}</td>
                            <td>{{ $order->shipping_method }}</td>
                            <td>{{ $order->payment_method }}</td>
                            <td class="text-center">
                                @if($order->order_position == 0)<span class="badge rounded-pill alert-info">Pending</span>@endif
                                @if($order->order_position == 1)<span class="badge rounded-pill alert-primary">Process</span>@endif
                                @if($order->order_position == 2)<span class="badge rounded-pill alert-warning">On Delivery</span>@endif
                                @if($order->order_position == 3)<span class="badge badge-success">Delivered</span>@endif
                            </td>
                            <td>
                                <a href="{{ url('admin/user-order-detail',$order->id) }}" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr class="text-center">
                            <td colspan="7"><p>No Order found</p></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {!! $orders->links() !!}
        </div>
    </div>
</div>    
<!-- end modal -->
@endsection

@push('styles')
<style>
        .pagination{
            float: right;
            margin-top: 10px;
        }
</style>
@endpush
