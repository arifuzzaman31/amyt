<table>
	<thead>
	  <tr>
        <th>OrderID</th>
        <th>Order Date</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Payment</th>
        <th>Address</th>
        <th>Order In Site</th>
        <th>Spend Amount</th>
        <th>Cancel Item</th>
        <th>Refund</th>
        <th>Payment Method</th>
        <th>Payment Gateway</th>
	  </tr>
	</thead>
	<tbody>
        @foreach($customerindividualdata as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->order_date }}</td>
            <td>{{ $item->user->name }}</td>
            <td>{{ $item->user->email }}</td>
            <td>
                    {{ $item->user->phone }}
            </td>
            <td>{{ $item->total_price }}</td>
            <td>{{ $item->user->address }}</td>
            <td>
                @if($item->order_position == 0)<p>Pending</p>@endif
                @if($item->order_position == 1)<p>Processing</p>@endif
                @if($item->order_position == 2)<p>On Delivery</p>@endif
                @if($item->order_position == 3)<p>Delivered</p>@endif
            </td>
            <td>{{ $item->total_price }}</td>
            <td>{{ $item->refund_count }}</td>
            <td>{{ $item->refunded_amount }}</td>
            <td>
                {{ $item->payment_via == 0 ? 'COD' : 'Online' }}
            </td>
            <td>{{ $item->payment_method_name }}</td>
        </tr>	
        @endforeach
	</tbody>
</table>