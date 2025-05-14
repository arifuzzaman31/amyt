@extends('layout.app')
@section('title', 'Order Details | Aranya')
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
<div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                    <h4>Order-{{ $orders->order_id }}</h4>
                </div>                 
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div>
                <!-- <p class="mb-2"><b>Tracking ID: </b>{{ $orders->tracking_id }}</p> -->
                <table class="table table-bordered table-hover mb-4">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Product Name</th>
                            <th>Picture</th>
                            <th>Size</th>
                            <th>Fabric</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Is Refunded</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $key => $detail)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $detail->product->product_name }}</td>
                                <td>
                                    <img height="60" src="{{$detail->product->product_image}}" />
                                </td>
                                <td>{{ $detail->size->size_name }}</td>
                                <td>{{ $detail->fabric->fabric_name }}</td>
                                <td>{{ $detail->selling_price }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->is_refunded == 1 ? 'Refunded' : 'N/A'}}</td>
                                <td>{{ $detail->total_selling_price }}</td>
                            </tr>
                            @empty
                            <tr  class="text-center">
                                <td colspan="9"><p>No Data found</p></td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="8" class="text-right">Total: </td>
                            <td>{{ $orders->total_price }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>  
        </div>
        <div class="row widget-content widget-content-area">
            
                    <div class="col-md-4 ml-1 card py-2">
                        <h6 class="text-info">Shipping Address</h6>
                        @if($orders->is_same_address != 1)<p class="">
                            {{
                                $orders->user_shipping_info->street_address
                            }}
                            <br />
                            {{ $orders->delivery->post_code }},
                            <br />
                            {{
                                $orders->user_shipping_info->city
                            }},{{
                                $orders->user_shipping_info->country
                            }}
                        </p>
                        @else
                        <p class="">Same as Billing Address</p>
                        @endif
                    </div>
                    <div class="col-md-4 ml-2 card py-2">
                        <h6 class="text-info">Billing Address</h6>
                        <p class="">
                            {{
                                $orders->user_billing_info->street_address
                            }}
                            <br />
                            {{
                                $orders->user_billing_info->post_code
                            }}, <br />
                            {{ $orders->user_billing_info->city }},
                            {{ $orders->delivery->country }}
                        </p>
                    </div>              
              
                
            <div class="col-md-6 col-12 mt-3">
                <h6 class="text-center">Delivery Progress</h6>
                <table class="table table-bordered table-hover mb-4">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Status</th>
                            <th>Date</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($orders->delivery))
                            @if($orders->delivery->process_state == 1)
                            <tr>
                                <td>1</td>
                                <td>{{ $orders->delivery->process_value }}</td>
                                <td>{{ $orders->delivery->process_date }}</td>
                            </tr>
                            @endif

                            @if($orders->delivery->on_delivery_state == 2)
                            <tr>
                                <td>1</td>
                                <td>{{ $orders->delivery->on_delivery_value }}</td>
                                <td>{{ $orders->delivery->on_delivery_date }}</td>
                            </tr>
                            @endif

                            @if($orders->delivery->delivery_state == 1)
                            <tr>
                                <td>1</td>
                                <td>{{ $orders->delivery->delivery_value }}</td>
                                <td>{{ $orders->delivery->delivery_date }}</td>
                            </tr>
                            @endif
                        @else 
                            <tr>
                                <td>1</td>
                                <td>Pending</td>
                                <td>{{ $orders->order_date }}</td>
                            </tr>
                        @endif
                         
                    </tbody>
                </table>
                </div>  
            </div>  
        </div>
    </div>
</div>    
<!-- end modal -->
@endsection

