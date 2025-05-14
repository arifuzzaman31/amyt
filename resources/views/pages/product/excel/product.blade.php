<table class="table">
    <thead>
        <tr>
            <th>id</th>
            <th>title</th>
            <th>description </th>
            <th>availability </th>
            <th>condition </th>
            <th>price</th>
            <th>link</th>
            <th>image_link</th>
            <th>brand</th>
            <th>google_product_category</th>
            <th>quantity_to_sell_on_facebook</th>
            <th>sale_price</th>
            <th>custom_label_0</th>
            <th>custom_label_1</th>
            <th>custom_label_2</th>
            <th>sale_price_effective_date</th>
            <th>item_group_id</th>
            <th>gender</th>
            <th>color</th>
            <th>size</th>
            <th>age_group</th>
            <th>material</th>
            <th>pattern</th>
            <th>shipping</th>
            <th>shipping_weight</th>
            <th>style</th>
            <th>availability_circle_origin</th>
            <th>availability_polygon_coordinates</th>
            <th>address</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $value)
            @if(isset($value))
                @php
                    $link = config('app.front_url').'products/'.($value->subcategory->slug ?? $value->category->slug).'/'.$value->id;
                @endphp
                @foreach ($value->inventory as $variant)
                    @php
                        $salePrice = 0;
                        if($variant->discount){
                            if($variant->discount->discount_type == 'percentage'){
                                $salePrice = $variant->mrp - ($variant->mrp * ($variant->discount->discount_amount / 100));
                            } else {
                                $salePrice = $variant->mrp - $variant->discount->discount_amount;
                            }
                        } else {
                            $salePrice = $variant->mrp;
                        }

                    @endphp
                    <tr>
                        <td>{{ $variant->id }}</td>
                        <td>{{ $value->product_name }}</td>
                        <td>{{ strip_tags($value->description) }}</td>
                        <td>in stock</td>
                        <td>new</td>
                        <td>{{ $variant->mrp }}</td>
                        <td>{{ $link }}</td>
                        <td>{{ $value->image_one }}</td>
                        <td>{{ $value->product_brand[0]->brand_name ?? 0 }}</td>
                        <td>Apparel &amp; Accessories &gt; Clothing Accessories</td>
                        <td>{{ $variant->stock ?? 0 }}</td>
                        <td>{{ $variant->mrp > $salePrice ? floor($salePrice) : '' }}</td>
                        <td>{{$value->category->category_name}}</td>
                        <td>{{$value->subcategory->category_name ?? ''}}</td>
                        <td>{{$value->campaign[0]->campaign_name ?? ''}}</td>
                        <td></td>
                        <td>{{ $value->has_variation == 1 ? $value->design_code : ''}}</td>
                        <td></td>
                        <td>{{ $value->has_variation == 1 ? $variant->colour->color_name ?? '' : '' }}</td>
                        <td>{{ $value->has_variation == 1 ? $variant->size->size_name ?? '' : ''}}</td>
                        <td>all ages</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $value->weight }} g</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
