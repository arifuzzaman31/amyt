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
        <th>Amount</th>
        <th>Payment Gateway</th>
	  </tr>
	</thead>
	<tbody>
    
	@foreach($paymentdata as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->order_date }}</td>
            <td>{{ $item->user->name }}</td>
            <td>{{ $item->user->email }}</td>
            <td>{{ $item->user->phone }}</td>
            <td>{{ $item->total_price }}</td>
            <td>{{ $item->user->address }}</td>
            <td>{{ $item->total_price }}</td>
            <td>{{ $item->payment_method_name }}</td>
        </tr>
	@endforeach
	</tbody>
</table>