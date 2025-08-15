<table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
    <tbody>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>Hello {{ $order->customer_name }},</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>Thank you for shopping with {{config('app.name')}}. Your order details are as
                follows:</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td align="center">
                <table width="94%" border="1" cellspacing="0" cellpadding="5"
                    style="border:1px solid #d6d4d5;border-collapse:collapse;background-color:#f8f8f8">
                    <tbody>
                        <tr>
                            <td>
                                <h4
                                    style="font-size:20px;padding:5px 0px;margin:0px;font-weight:bold">
                                    ORDER DETAILS </h4>
                                <p style="margin:5px 2px;background:#d5d5d5;height:1px">
                                </p>
                                <p style="padding-bottom:2px"> Order: <span>
                                        {{ $order->tracking_number }}
                                        Place on {{ Carbon\Carbon::parse($order->created_at)->format('d F, Y H:i:s') }}</span>
                                    <br><br> Payment : @if($order->payment_gateway == "BANKWIRE_TRANSFER") Bank Wire @else {{ $order->payment_gateway }} @endif
                                </p>
                                @if (count($order->orderMessage) > 0)
                                <p style="padding-bottom:2px"> Message: <span>
                                    {{ $order->orderMessage[0]->custom_message }}
                                </p>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td align="center">
                <table width="94%" border="1" cellspacing="0" cellpadding="5"
                    style="border:1px solid #d6d4d5;border-collapse:collapse;background-color:#f8f8f8">
                    <tbody>
                        <tr>
                            <td align="center">
                                <h6 style="font-size:16px;padding:5px 0px;margin:0px">
                                    Reference</h6>
                            </td>
                            <td>
                                <h6 style="font-size:16px;padding:5px 0px;margin:0px">
                                    Product </h6>
                            </td>
                            <td>
                                <h6 style="font-size:16px;padding:5px 0px;margin:0px">
                                    Unit price </h6>
                            </td>
                            <td>
                                <h6 style="font-size:16px;padding:5px 0px;margin:0px">
                                    Quantity </h6>
                            </td>
                            <td>
                                <h6 style="font-size:16px;padding:5px 0px;margin:0px">
                                    Total price </h6>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
                        </tr>
                        @foreach ($order->products as $product)
                            <tr>
                                <td bgcolor="#FFFFFF">
                                    @if(isset($product->attributeCombinations))
                                        @foreach ($product->attributeCombinations as $combination)
                                            @if($combination->id == $product->pivot->product_attribute_id)
                                                {{$combination->reference_code}}

                                                {{-- {{$combination->all_combination}} --}}
                                            @endif
                                        @endforeach
                                    @endif
                                    {{-- {{ $product->reference_code ? $product->reference_code : null }} --}}
                                    {{-- {{ $product->product_combinations[0]->reference_code ? $product->product_combinations[0]->reference_code : null }} --}}
                                </td>
                                <td bgcolor="#FFFFFF">{{ $product->name }} -
                                    @if(isset($product->attributeCombinations))
                                        @foreach ($product->attributeCombinations as $combination)
                                            @if($combination->id == $product->pivot->product_attribute_id)
                                                {{$combination->all_combination}}
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td bgcolor="#FFFFFF" align="right">

                                    {{ currency_with_format($product->pivot->unit_price) }}</td>
                                <td bgcolor="#FFFFFF" align="center">
                                    {{ $product->pivot->order_quantity }}</td>
                                <td bgcolor="#FFFFFF" align="right">
                                    {{ currency_with_format($product->pivot->subtotal) }}</td>
                                {{-- <td bgcolor="#FFFFFF">PTKT3PFACAAA</td>
                            <td bgcolor="#FFFFFF">asdasd </td>
                            <td bgcolor="#FFFFFF" align="right">asdasd</td>
                            <td bgcolor="#FFFFFF" align="center">asdasd</td>
                            <td bgcolor="#FFFFFF" align="right">asdasd</td> --}}
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><strong>Sub Total</strong>
                            </td>
                            <td align="right">{{ currency_with_format($order->total) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><strong>Discount</strong>
                            </td>
                            <td align="right">{{ currency_with_format($order->product_discount) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><strong>Coupon {{(isset($order->coupon)) ? '('.$order->coupon->code.')' : '';}}</strong>
                            </td>
                            <td align="right">{{ currency_with_format($order->discount) }}</td>
                        </tr>
                        {{-- <tr>
                            <td colspan="4" align="right"><strong>Coupon {{(isset($order->coupon)) ? '('.$order->coupon->code.')' : '';}}</strong>
                            </td>
                            <td align="right">{{ currency_with_format($order->discount) }}</td>
                        </tr> --}}
                        <tr>
                            <td colspan="4" align="right"><strong>Assembly Charges</strong>
                            </td>
                            <td align="right">{{ currency_with_format($order->assembly_charges) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><strong>CGST ({{$order->average_cgst_rate}}%)</strong>
                            </td>
                            <td align="right">{{ currency_with_format($order->total_cgst) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><strong>SGST ({{$order->average_sgst_rate}}%)</strong>
                            </td>
                            <td align="right">{{ currency_with_format($order->total_sgst) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><strong>Shipping</strong>
                            </td>
                            <td align="right">{{ currency_with_format($order->delivery_fee) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><strong>Grand
                                    Total</strong></td>
                            <td align="right" width="80px">{{ currency_with_format($order->paid_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td align="center">
                <table width="94%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td width="48%">
                                <table width="100%" border="1" cellspacing="0"
                                    cellpadding="5"
                                    style="border:1px solid #d6d4d5;border-collapse:collapse;background-color:#f8f8f8">
                                    <tbody>
                                        <tr>
                                            <td style="color:#777777">
                                                <h5
                                                    style="color:#565457;font-size:18px;padding:5px 0px;margin:0px">
                                                    DELIVERY ADDRESS </h5>
                                                <p
                                                    style="margin:0px 2px;background:#d5d5d5;height:1px">
                                                </p>
                                                <p> <strong>{{ $order->customer_name }}</strong><br>
                                                    {{-- {{ $order->order_shipping_address->address['street_address'] }}
                                                    <br>
                                                    {{ $order->order_shipping_address->address['city'] }}
                                                    <br>
                                                    {{ $order->order_shipping_address->address['state'] }}
                                                    <br>
                                                    {{ $order->order_shipping_address->address['zip'] }}
                                                    <br>
                                                    {{ $order->order_shipping_address->address['country'] }}
                                                    <br> --}}
                                                    {{$order->shipping_address_detail}}
                                                    {{-- {{ isset($order->order_shipping_address->address['contactNumber']) ? $order->order_shipping_address->address['contactNumber'] : $order->customer_contact }} <br> --}}
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td width="4%">&nbsp;</td>
                            <td width="48%">
                                <table width="100%" border="1" cellspacing="0"
                                    cellpadding="5"
                                    style="border:1px solid #d6d4d5;border-collapse:collapse;background-color:#f8f8f8">
                                    <tbody>
                                        <tr>
                                            <td style="color:#777777">
                                                <h5
                                                    style="color:#565457;font-size:18px;padding:5px 0px;margin:0px">
                                                    BILLING ADDRESS </h5>
                                                <p
                                                    style="margin:0px 2px;background:#d5d5d5;height:1px">
                                                </p>
                                                <p> <strong>{{ $order->customer_name }}</strong><br>
                                                    {{$order->billing_address_detail}}
                                                    {{-- {{ $order->order_billing_address->address['street_address'] }}
                                                    <br>
                                                    {{ $order->order_billing_address->address['city'] }}
                                                    <br>
                                                    {{ $order->order_billing_address->address['state'] }}
                                                    <br>
                                                    {{ $order->order_billing_address->address['zip'] }}
                                                    <br>
                                                    {{ $order->order_billing_address->address['country'] }}
                                                    <br>
                                                    {{ $order->order_billing_address->address['contactNumber'] ? $order->order_billing_address->address['contactNumber'] : $order->customer_contact }} <br> --}}
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                <table width="94%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td>You can review your order and download your invoice from
                                the <a href="{{config('shop.shop_url')}}/my-accounts#/my-orders"
                                    target="_blank"
                                    data-saferedirecturl="">'Order
                                    history'</a> section of your customer account by
                                clicking <a href="{{config('shop.shop_url')}}/my-accounts"
                                    target="_blank"
                                    data-saferedirecturl="">'My
                                    account'</a> on our shop.</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>
