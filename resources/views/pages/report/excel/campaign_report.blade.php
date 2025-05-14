<table>
	<thead>
		<tr>
			<th>SKU</th>
			<th>Category</th>
			<th>Sub Category</th>
			<th>Brand</th>
			<th>Vendor</th>
			<th>Designer</th>
			<th>Embellishment</th>
			<th>Making</th>
			<th>Season</th>
			<th>Variety</th>
			<th>Fit</th>
			<th>Artist</th>
			<th>Consignment</th>
			<th>Ingredients</th>
			<th>Fragile</th>
			<th>Fragile Charge</th>
			<th>Weight</th>
			<th>Lead Time</th>
			<th>Color</th>
			<th>Design code</th>
			<th>Composition</th>
			<th>Sales Quantity</th>
			<th>Total Sales Amount</th>
			<th>Discount</th>
			<th>Amount without VAT</th>
			<th>Amount with VAT</th>
			<th>Campaign ID</th>
			<th>Campaign staring date</th>
			<th>Campaign name</th>
			<th>Campaign end date</th>
		</tr>
	</thead>
	<tbody>

		@foreach($campaigndata as $item)
		<tr>
			<td>{{ $item->p_sku }}</td>
			<td>{{ $item->product->category->category_name }}</td>
			<td>{{ $item->product->subcategory->category_name }}</td>
			<td>
				@if($item->product->product_brand && count($item->product->product_brand) > 0)
				@foreach($item->product->product_brand as $brand)
				<p>
					{{ $brand->brand_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_vendor && count($item->product->product_vendor) > 0)
				@foreach($item->product->product_vendor as $verdor)
				<p>
					{{ $verdor->vendor_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_designer && count($item->product->product_designer) > 0)
				@foreach($item->product->product_designer as $designer)
				<p>
					{{ $designer->designer_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_embellishment && count($item->product->product_embellishment) > 0)
				@foreach($item->product->product_embellishment as $embellishment)
				<p>
					{{ $embellishment->embellishment_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_making && count($item->product->product_making) > 0)
				@foreach($item->product->product_making as $making)
				<p>
					{{ $making->making_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_season && count($item->product->product_season) > 0)
				@foreach($item->product->product_season as $season)
				<p>
					{{ $season->season_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_variety && count($item->product->product_variety) > 0)
				@foreach($item->product->product_variety as $variety)
				<p>
					{{ $variety->variety_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_fit && count($item->product->product_fit) > 0)
				@foreach($item->product->product_fit as $fit)
				<p>
					{{ $fit->fit_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_artist && count($item->product->product_artist) > 0)
				@foreach($item->product->product_artist as $artist)
				<p>
					{{ $artist->artist_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_consignment && count($item->product->product_consignment) > 0)
				@foreach($item->product->product_consignment as $consignment)
				<p>
					{{ $consignment->consignment_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td>
				@if($item->product->product_ingredient && count($item->product->product_ingredient) > 0)
				@foreach($item->product->product_ingredient as $ingredient)
				<p>
					{{ $ingredient->ingredient_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td> {{ $item->fragile ? 'Yes' : 'No' }}</td>
			<td> {{ $item->fragile_charge }}</td>
			<td> {{ $item->weight }}</td>
			<td> {{ $item->lead_time }}</td>
			<td> @if($item->colour && $item->colour->color_name)<p>{{ $item->colour->color_name }}</p>@endif</td>
			<td> {{ $item->design_code }}</td>
			<td>
				@if($item->product->product_fabric && count($item->product->product_fabric) > 0)
				@foreach($item->product->product_fabric as $fabric)
				<p>
					{{ $fabric->fabric_name }}
				</p>
				@endforeach
				@endif
			</td>
			<td> {{ $item->sales_quantity }}</td>
			<td> {{ $item->total_selling_amount }}</td>
			<td> 0</td>
			<td> {{ $item->total_selling_amount }}</td>
			<td> {{ $item->total_selling_amount+$item->total_vat_amount }}</td>
			<td> {{ $item->product->campaign[0]->id }}</td>
			<td> {{ $item->product->campaign[0]->campaign_start_date  }}</td>
			<td> {{ $item->product->campaign[0]->campaign_name  }}</td>
			<td> {{ $item->product->campaign[0]->campaign_expire_date }}</td>
		</tr>
		@endforeach
	</tbody>
</table>