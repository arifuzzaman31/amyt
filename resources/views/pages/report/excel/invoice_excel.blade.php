<table>
  <thead>
    <tr>
      <th>OrderID</th>
      <th>Order Date</th>
      <th>Customer</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Address</th>
      <th>Delivery</th>
      <th>Item Qty</th>
      <th>Amount</th>
      <th>Payment Method</th>
      <th>Payment Gateway</th>
    </tr>
  </thead>
  <tbody>

    @foreach($orders as $order)
    <tr>
      <td>{{ $order->id }}</td>
      <td>{{ $order->order_date }}</td>
      <td>{{ $order->user_shipping_info->full_name }}</td>
      <td>{{ $order->user_shipping_info->phone }}</td>
      <td>{{ $order->user_shipping_info->email }}</td>
      <td>{{ $order->user_shipping_info->street_address }}</td>
      <td>
        @if($order->order_position == 0)<p>Pending</p>@endif
        @if($order->order_position == 1)<p>Processing</p>@endif
        @if($order->order_position == 2)<p>On Delivery</p>@endif
        @if($order->order_position == 3)<p>Delivered</p>@endif
      </td>
      <td>{{ $order->total_item }}</td>
      <td>{{ $order->total_price }}</td>
      <td>{{ $order->total_price - $order->buying_sum}}</td>
      <td>
        {{ $order->payment_via == 0 ? 'COD' : 'Online' }}
      </td>
      <td>{{ $order->payment_method_name }}</td>
    </tr>
    @endforeach
  </tbody>
</table>