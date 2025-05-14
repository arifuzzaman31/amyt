<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html;" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice-{{$order_info->order_id}}</title>
    <style>
    @font-face {
        font-family: "solemn";
        font-style: normal;
        font-weight: normal;
        src : url('{{ asset("fonts/kalpurush.ttf") }}');
    }

    body {
        width: 100%;
        line-height: 0.46rem;
        font-family: 'solemn', sans-serif, arial;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin: 0 auto;
    }

    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px 3px;
        font-size: 12px;
    }

    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px 3px;
        font-weight: 500;
        font-size: 12px;
    }

    a {
        text-decoration: none;
    }

    div {
        background-color: #ffffff;
        padding-top: 1.5rem;
        margin: 0 auto;
        height: fit-content;

    }
    </style>
</head>

<body>
    <div>
        <table>
            <tr>
                <th style="border: none; text-align: left">
                    <img src="{{ asset('admin-assets/assets/img/aranya-logo-dark.png') }}" alt="Aranya Logo"
                        width="90px" />
                </th>
                <td style="width: 80%;border:none"></td>

                <td style="border: none; ">
                    <p style="display:flex; column-gap:6px;align-items:center;color: #545255"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-globe" viewBox="0 0 16 16">
                            <path
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" />
                        </svg>online@aranya.com.bd
                    </p>
                    <p style="display:flex; column-gap:6px;align-items:center;color: #545255"><svg
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-envelope-fill" viewBox="0 0 16 16">
                            <path
                                d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z" />
                        </svg> <span>www.aranya.com.bd</span>
                    </p>

                </td>
            </tr>
        </table>
        <hr style="
        height: 5px;background-color: #3b5576;
        border-redius:10px;
        margin: 0 auto;" />
        <table>
            <tr>
                <td style="border: none; color: #272627cc;font-size:12px;font-weight:bolder">
                    INVOICE NO: #{{$order_info->order_id}}
                </td>
                <th style="border: none"></th>

                <td style="border: none; color: #272627cc;font-size:12px;font-weight:bolder;text-align:right">DATE:
                    {{ date("j M,Y",strtotime($order_info->order_date)) }}
                </td>
            </tr>
            <tr style="border: none; background-color: #fff">
                <td style="border: none; color: #272627cc;">
                    {!! $order_info->user_shipping_info->full_name !!}
                </td>
                <th style="border: none"></th>

                <td style="border: none;"></td>
            </tr>
            <tr>
                <td style="border: none; color: #272627cc;">
                    Phone: {!! $order_info->user_shipping_info->phone !!}
                </td>
                <th style="border: none"></th>

                <td style="border: none; color: #272627cc;font-size:12px;font-weight:bolder;text-align:right">PAYMENT
                    METHOD</td>
            </tr>
            <tr style="border: none; background-color: #fff">
                <td style="border: none; color: #272627cc;">
                    Email: {{ $order_info->user_shipping_info->email }}
                </td>
                <th style="border: none"></th>

                <td style="border: none; color: #272627cc;text-align:right">
                    {{ $order_info->payment_via == 1 ? 'Online Paid' : 'Cash on delivery'}}
                </td>
            </tr>
            <tr style="border: none; background-color: #fff;width:25%;text-wrap: balance;">
                <td style="border: none; color: #272627cc;">
                    <span style="width: 20%;text-wrap: balance;">

                        Address:
                        {!! $order_info->user_shipping_info->street_address !!},
                        <p>{!! $order_info->user_shipping_info->city !!},{!! $order_info->user_shipping_info->country
                            !!}</p>
                    </span>
                </td>
                <th style="border: none"></th>

                <td style="border: none; color: #272627cc;text-align:right">Delivery
                    Option:{{$order_info->delivery_platform}}</td>
            </tr>
        </table>

        <table style="margin-top: 2rem">
            <tr style="background-color: #3b5576; border: none">
                <th scope="col" style="border: none; color: #ffffff">PRODUCT </th>
                <th scope="col" style="border: none; color: #ffffff;text-align:center">WEIGHT</th>
                <th scope="col" style="border: none; color: #ffffff;text-align:center">SIZE</th>
                <th scope="col" style="border: none; color: #ffffff;text-align:center">SKU</th>
                <th scope="col" style="border: none; color: #ffffff;text-align:center">VAT</th>
                <th scope="col" style="border: none; color: #ffffff;text-align:center">VAT AMOUNT</th>
                <th scope="col" style="border: none; color: #ffffff;text-align:center">QTY</th>
                <th scope="col" style="border: none; color: #ffffff;text-align:center">DISCOUNT</th>
                <th scope="col" style="border: none; color: #ffffff;text-align:center">PRICE</th>
                <th scope="col" style="border: none; color: #ffffff ;text-align: right;">SUBTOTAL</th>
            </tr>
            @foreach($order_info->order_details as $key => $value)

            <tr>
                <td style="border: none;max-width: 15%;text-wrap: wrap;">{{ $value->product->product_name }}</td>
                <td style="border: none;text-align:center">{{ $value->product->weight??0 }}</td>
                <td style="border: none;text-align:center">{{ $value->size->size_name}}</td>
                <td style="border: none;text-align:center">{{ $value->item_sku}}</td>
                <td style="border: none;text-align:center">{{ $value->vat_rate}}%</td>
                <td style="border: none;text-align:center">{{ $value->charge_vat_amount}}</td>
                <td style="border: none;text-align:center">{{ $value->quantity }}</td>
                <td style="border: none;text-align:center">{{ $value->charge_total_discount }}</td>
                <td style="border: none;text-align:center">{{ $value->charge_selling_price }}</td>
                <td style="border: none; text-align: right;">{{ $value->total_charge_selling_price }}</td>
            </tr>

            @endforeach
        </table>



        <div style="float: right;width:34%">
            <table style="margin-top: .5rem;padding:2px 4px; float: right;background-color: #3b5576;color:#fff">
                <tr style="border: none;">
                    <td style="border: none;">SUB-TOTAL</td>
                    <td style="border: none;text-align: right">{{ $order_info->charge_total_price }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">VAT</td>
                    <td style="border: none;text-align: right"> {{ $order_info->charge_vat_amount }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">DISCOUNT</td>
                    <td style="border: none;text-align: right"> {{ $order_info->charge_discount }}</td>
                </tr>

                <tr style="border: none;">
                    <td style="border: none;">SHIPPING

                    </td>
                    <td style="border: none;text-align: right"> {{ $order_info->charge_shipping_amount }}</td>
                </tr>
                <tr style="border: none;font-size:15px;font-weight:bolder">
                    <td style="border: none;">TOTAL
                    </td>

                    <td style="border: none;text-align: right">
                        {{ $order_info->charge_total_price + $order_info->charge_vat_amount + $order_info->charge_shipping_amount }}
                    </td>
                </tr>
                <tr style="border: none;font-size:15px;font-weight:bolder">
                    <td style="border: none;">CURRENCY
                    </td>

                    <td style="border: none;text-align: right">
                        {{ $order_info->charged_currency }}
                    </td>
                </tr>
            </table>

        </div>
        @if($order_info->user_note)
        <p style="border: none;margin-left:4px; color: #272627cc;font-size:15px;font-weight:bolder">CUSTOMER NOTE</p>
        <p style="margin-left:4px;font-size: 15px">{!! $order_info->user_note !!}</p>
        @endif
    </div>
</body>

</html>