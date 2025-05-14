<table>
	<thead>
	  <tr>
        <th>Customer</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Payment</th>
        <th>Address</th>
        <th>Total Order in Aranya</th>
        <th>Total Amount Spent</th>
        <th>Total Item Cancel</th>
        <th>Total Refund</th>
        <th>Total Refund Amount</th>
	  </tr>
	</thead>
	<tbody>
        @foreach($lifetimevaluedata as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->phone }}</td>
                <td>{{ $item->total_spent_amount }}</td>
                <td>{{ $item->address }}</td>
                <td>{{ $item->count_order }}</td>
                <td>{{ $item->total_spent_amount }}</td>
                <td>{{ $item->cancel_count }}</td>
                <td>
                    {{ $item->refund_count }}
                </td>
                <td>{{ $item->refunded_amount }}</td>
            </tr>	
        @endforeach
	</tbody>
</table>