<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
            background-color: #fff;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            /* background: #eee; */
            /* border-bottom: 1px solid #ddd; */
            font-weight: bold;
            text-align: center;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            /* border-bottom: 1px solid #eee; */
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            /* border-top: 2px solid #eee; */
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: right;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    @php
    $contactDetails = $settings->options['contactDetails'];
    $customer = $order->customer;
    $shippingAddress = $order->orderShippingAddress;
    $billingAddress = $order->orderBillingAddress;
    $products = $order->products;
    $settings = $settings->options;
    $authorDetails = $settings['contactDetails'];
    $authorLocation = $authorDetails['location'];
    $currency = isset($settings['currency']) ? $settings['currency'] : 'USD';
@endphp
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td><img src="{{ config('app.url') }}/images/harvin-chairs-logo.png" style="width:100%; max-width:200px; top-padding:10px;"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 350px;">
                                    {!!  $authorLocation['formattedAddress'] !!}
                            </td>

                            <td>
                                Invoice No. : {{$order->tracking_number }}<br>
                                Invoice date : {{$order->created_at->format('M d, Y')}}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td style="width: 350px;">
                                <strong>Billing Address</strong><br/>
                                {{ $customer['first_name'] }} {{ $customer['last_name'] }}
                                <br>
                                {{ $customer['email'] }} {{ $order->customer_contact }} <br>
                                <span class="full-address" style="max-width: 70px; word-wrap: break-word;">
                                    {!! $billingAddress->full_address !!}
                                </span>
                                <br>
                                <br>
                                <br>
                                <strong>Shipping Address</strong><br/>
                                {{ $customer['first_name'] }} {{ $customer['last_name'] }}
                                <br>
                                {{ $customer['email'] }} {{ $order->customer_contact }} <br>
                                <span class="full-address" style="max-width: 70px; word-wrap: break-word;">
                                    {!! $shippingAddress->full_address !!}
                                </span>
                            </td>


                            <td>
                                <div>
                                    Order No: {{$order->id }}
                                </div>
                                <div>
                                    Order Date: {{$order->created_at->format('M d, Y') }}
                                </div>
                                {{-- @if ($order->payment_type)
                                    <div>
                                        <strong>Payment Method</strong>
                                        <p>{{$order->payment_type ?? ''}}</p>
                                    </div>
                                @endif --}}
                                <div>
                                    GST No. {{env('GST_NO')}}
                                </div>
                                {{-- <div>
                                    <strong>Order Total</strong>
                                    <p>@money($order->paid_total, $currency)</p>
                                </div> --}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>

        @php
        // Check if any of the products have gst_percentage greater than 0
        $showTaxColumns = false;
        foreach ($products as $product) {
            if ($product->pivot['gst_percentage'] > 0) {
                $showTaxColumns = true;
                break;
            }
        }
        @endphp

        <table cellpadding="0" cellspacing="0" border="1" style=" border-collapse: collapse;">
            <tr class="heading">
                <td>{{ $translated_text['products'] }}</td>
                <td style="text-align: center;">SKU</td>
                @if($showTaxColumns)
                    <td style="text-align: center;">{{ $translated_text['unit_price'] }}</td>
                    <td style="text-align: center;">{{ $translated_text['tax_rate'] }}</td>
                    <td style="text-align: center;">{{ $translated_text['tax_amount'] }}</td>
                @endif
                <td style="text-align: center;">{{ $translated_text['price'] }}</td>
                <td style="text-align: center;">{{ $translated_text['quantity'] }}</td>
                <td style="text-align: right;">{{ $translated_text['total'] }}</td>
            </tr>

            @foreach ($products as $product)
                <tr class="item">
                    <td>{{ $product['name'] }}
                        @if (isset($product->attributeCombinations))
                            @foreach ($product->attributeCombinations as $combination)
                                @if ($combination->id == $product->pivot->product_attribute_id)
                                    <br /> {{ $combination->all_combination }}
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if (isset($product->attributeCombinations))
                            @foreach ($product->attributeCombinations as $combination)
                                @if ($combination->id == $product->pivot->product_attribute_id)
                                    {{ $combination->reference_code  }}
                                @endif
                            @endforeach
                        @endif
                    </td>
                    @if($product->pivot['gst_percentage'] > 0)
                        <td style="text-align: center;">
                            @if ($product->pivot['price_without_gst'] > 0)
                            {{ $product->pivot['price_without_gst'] }}
                            @else
                            {{ $product->pivot['unit_price'] }}
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($product->pivot['price_without_gst'] > 0)
                            {{ $product->pivot['gst_percentage'] }}&#37;
                            @else
                                0&#37;
                            @endif
                           
                        </td>
                        <td style="text-align: center;">
                            @if ($product->pivot['price_without_gst'] > 0)
                                {{ $product->pivot['unit_price'] -  $product->pivot['price_without_gst'] }}
                            @else
                                0.00
                            @endif
                        </td>
                    @endif
                    <td style="text-align: center;">{{ $product->pivot['unit_price'] }}</td>
                    <td style="text-align: center;">{{ $product->pivot['order_quantity'] }}</td>
                    <td style="text-align: right;">@money($product->pivot['unit_price'] *  $product->pivot['order_quantity'], $currency)</td>
                </tr>
            @endforeach
        </table>


        <table>
            <tr>
                <td>
                    <br/>
                </td>
            </tr>
        </table>

        <table border="1" style=" border-collapse: collapse;width: 350px;margin-left:auto;">
            {{-- <tr class="total">
                <td> --}}
                    <tr>
                        <td>{{ $translated_text['products_total'] }} : </td>
                        <td style="text-align: right;">@money(($order->total), $currency)</td>
                    </tr>
                    @if($order->product_discount > 0)
                    <tr>
                        <td>Products Discount : </td>
                        <td style="text-align: right;">@money(($order->product_discount), $currency)</td>
                    </tr>
                    @endif
                    <tr>
                        <td>{{ $translated_text['subtotal'] }} : </td>
                        <td style="text-align: right;">@money(($order->total - $order->product_discount), $currency)</td>
                    </tr>
                    @if(isset($order->coupon))
                    <tr>
                        <td>Coupon : {{ isset($order->coupon) ? '('.$order->coupon->code.')' : '' }}</td>
                        <td style="text-align: right;">@money($order->discount, $currency)</td>
                    </tr>
                    @endif
                    @if($order->assembly_charges > 0)
                    <tr>
                        <td>Assembly Charges : </td>
                        <td style="text-align: right;">@money($order->assembly_charges, $currency)</td>
                    </tr>
                    @endif
                    {{-- @if($order->average_cgst_rate > 0)
                    <tr>
                        <td>CGST ({{$order->average_cgst_rate}}%) : </td>
                        <td style="text-align: right;">@money($order->total_cgst, $currency)</td>
                    </tr>
                    @endif --}}
                    {{-- @if($order->average_sgst_rate > 0)
                    <tr>
                        <td>SGST ({{$order->average_sgst_rate}}%) : </td>
                        <td style="text-align: right;"> @money($order->total_sgst, $currency)</td>
                    </tr>
                    @endif --}}
                    <tr>
                        <td>{{ $translated_text['total'] }} : </td>
                        <td style="text-align: right;">
                            @money($order->paid_total, $currency)<br/>
                            Inclusive Of All Taxes
                        </td>
                    </tr>
                {{-- </td>
            </tr> --}}
        </table>
        <table>
            <tr>
                <td>
                    <br/>
                </td>
            </tr>
        </table>
        <table border="1" style=" border-collapse: collapse;">
            <tr>
                <td>
                    Amount in Words:<br/>
                    {{numberToWords($order->getOriginal('paid_total'))}} Only
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
