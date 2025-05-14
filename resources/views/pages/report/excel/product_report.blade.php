<table>
	<thead>
	  <tr>
        <th>SL</th>
        <th>Product Name</th>
        <th>Category</th>
        <th>Sub Category</th>
        <th>Design code</th>
        <th>Total Sale</th>
	  </tr>
	</thead>
	<tbody>
        @foreach($productdata as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->category->category_name }}</td>
                <td>{{ $item->subcategory->category_name }}</td>
                <td>{{ $item->design_code }}</td>
                <td>{{ $item->order_details_count }}</td>
            </tr>
        @endforeach
	</tbody>
</table>
