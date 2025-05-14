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
        <th>Refund Request Date</th>
        <th>Approved/Reject Date</th>
        <th>Refund Amount</th>
        <th>Reason</th>
        <th>Payment Mode</th>
        <th>Payment Gateway</th>
	  </tr>
	</thead>
	<tbody>
        @foreach($refunddata as $item)
            <tr>
                <td>{{ $item->order_id }}</td>
                <td>{{ $item->order->order_date }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->user->email }}</td>
                <td>{{ $item->user->phone }}</td>
                <td>{{ $item->order->total_price }}</td>
                <td>{{ $item->user->address }}</td>
                <td>{{ $item->refund_claim_date }}</td>
                <td>{{ $item->refund_date }}</td>
                <td>{{ $item->total_selling_price }}</td>
                <td>{{ $item->refund_claim_reason }}</td>
                <td>
                    {{ $item->order->payment_via == 0 ? 'COD' : 'Online' }}
                </td>
                <td>{{ $item->order->payment_method_name }}</td>
            </tr>	
        @endforeach
	</tbody>
</table>