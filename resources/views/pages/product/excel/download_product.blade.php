<table class="table">
    <thead>
        <tr>
            <th>BARCODE</th>
            <th>Design Code</th>
            <th>Name </th>
            <th>Short description </th>
            <th>VENDOR </th>
            <th>CATEGORY</th>
            <th>SUB CATEGORY</th>
            <th>BRAND</th>
            <th>Has Variation</th>
            <th>Designer</th>
            <th>Embellishment</th>
            <th>Composition</th>
            <th>Size</th>
            <th>Ingredients</th>
            <th>Making</th>
            <th>Lead Time</th>
            <th>Season</th>
            <th>Variety</th>
            <th>Fit</th>
            <th>Colour</th>
            <th>Artist Name</th>
            <th>Consignment</th>
            <th>CPU</th>
            <th>MRP</th>
            <th>BALANCE</th>
            <th>image One</th>
            <th>Image Two</th>
            <th>Image Three</th>
            <th>Image Four</th>
            <th>Image Five</th>
            <th>Dimension (H,W,L)</th>
            <th>Unit</th>
            <th>Weight</th>
            <th>Care </th>
            <th>Tags</th>
            <th>VAT</th>
            <th>Flat Colour</th>
            <th>Is_Fragile</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $value)
            @if(isset($value))
                    <tr>
                        <td>{{ $value->sku }}</td>
                        <td>{{ $value->product->design_code }}</td>
                        <td>{{ $value->product->product_name }}</td>
                        <td>{{ strip_tags($value->product->description) }}</td>
                        <td>
                            @if($value->product->product_vendor && count($value->product->product_vendor) > 0)
                                @foreach($value->product->product_vendor as $verdor)
                                <p>
                                    {{ $verdor->vendor_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $value->product->category->category_name }}</td>
                        <td>{{ $value->product->subcategory->category_name }}</td>
                        <td>
                            @if($value->product->product_brand && count($value->product->product_brand) > 0)
                                @foreach($value->product->product_brand as $brand)
                                <p>
                                    {{ $brand->brand_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $value->product->has_variation == 1 ? 'Yes' : 'No' }}</td>
                        <td>
                            @if($value->product->product_designer && count($value->product->product_designer) > 0)
                                @foreach($value->product->product_designer as $designer)
                                <p>
                                    {{ $designer->designer_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if($value->product->product_embellishment && count($value->product->product_embellishment) > 0)
                                @foreach($value->product->product_embellishment as $embellishment)
                                <p>
                                    {{ $embellishment->embellishment_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if($value->product->product_fabric && count($value->product->product_fabric) > 0)
                                @foreach($value->product->product_fabric as $fabric)
                                <p>
                                    {{ $fabric->fabric_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $value->size ? $value->size->size_name : ''}}</td>
                        <td>
                            @if($value->product->product_ingredient && count($value->product->product_ingredient) > 0)
                                @foreach($value->product->product_ingredient as $ingredient)
                                <p>
                                    {{ $ingredient->ingredient_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if($value->product->product_making && count($value->product->product_making) > 0)
                                @foreach($value->product->product_making as $making)
                                <p>
                                    {{ $making->making_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td> {{ $value->product->lead_time }}</td>
                        <td>
                            @if($value->product->product_season && count($value->product->product_season) > 0)
                                @foreach($value->product->product_season as $season)
                                <p>
                                    {{ $season->season_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if($value->product->product_variety && count($value->product->product_variety) > 0)
                                @foreach($value->product->product_variety as $variety)
                                <p>
                                    {{ $variety->variety_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if($value->product->product_fit && count($value->product->product_fit) > 0)
                                @foreach($value->product->product_fit as $fit)
                                <p>
                                    {{ $fit->fit_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td> @if($value->colour && $value->colour->color_name)<p>{{ $value->colour->color_name }}</p>@endif</td>
                        <td>
                            @if($value->product->product_artist && count($value->product->product_artist) > 0)
                                @foreach($value->product->product_artist as $artist)
                                <p>
                                    {{ $artist->artist_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if($value->product->product_consignment && count($value->product->product_consignment) > 0)
                                @foreach($value->product->product_consignment as $consignment)
                                <p>
                                    {{ $consignment->consignment_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $value->cpu }}</td>
                        <td>{{ $value->mrp }}</td>
                        <td>{{ $value->stock ?? 0 }}</td>
                        <td>{{ $value->product->image_one }}</td>
                        <td>{{ $value->product->image_two }}</td>
                        <td>{{ $value->product->image_three }}</td>
                        <td>{{ $value->product->image_four }}</td>
                        <td>{{ $value->product->image_five }}</td>
                        <td>{{ $value->product->height }},{{ $value->product->width }},{{ $value->product->length }}</td>
                        <td>{{ $value->product->unit }}</td>
                        <td>{{ $value->product->weight }}</td>
                        <td>
                            @if($value->product->product_care && count($value->product->product_care) > 0)
                                @foreach($value->product->product_care as $care)
                                <p>
                                    {{ $care->care_name }}
                                </p>
                                @endforeach
                            @endif
                        </td>
                        <td></td>
                        <td>{{ $value->product->vat->tax_percentage }}</td>
                        <td>{{$value->product->flat_colour}}</td>
                        <td> {{ $value->product->fragile ? 'Yes' : 'No' }}</td>
                    </tr>

            @endif
        @endforeach
    </tbody>
</table>
