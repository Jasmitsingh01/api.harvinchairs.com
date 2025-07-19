<style>
    #product_detail td{
        padding: 15px;
        border-bottom: 1px solid #f8f8f8;
        vertical-align: top;
    }
</style>
<table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
    <tbody>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>Dear Admin,</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>This email is to inform you that the following order has been canceled as per the customer's request. </td>
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
                                    Details of the Canceled Order:</h4>
                                <p style="margin:5px 2px;background:#d5d5d5;height:1px">
                                </p>
                                <p style="padding-bottom:2px">
                                    Customer Name: {{$order->customer_name}} <br/>
                                    Order Number: #{{$order->tracking_number}} <br/>
                                    Order Date: {{getFormatedDateTime($order->created_at)}}<br/>
                                    Total Amount: {{currency_with_format($order->paid_total)}}<br/>
                                    Cacellation Date: {{getFormatedDateTime($order->cancellation_date)}}<br/>
                                    Payment Method: {{$order->payment_gateway}}<br/>
                                    @if(!empty($order->cancel_reason))
                                        Reason for Cancellation: {{$order->cancel_reason}}
                                    @endif
                                </p>
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
                            <td>
                                <h4
                                    style="font-size:20px;padding:5px 0px;margin:0px;font-weight:bold">
                                    Items Canceled:</h4>
                                <p style="margin:5px 2px;background:#d5d5d5;height:1px">
                                </p>
                                <p style="padding-bottom:2px">
                                    <table id="product_detail">
                                        <tr>
                                            <td><strong>Name</strong></td>
                                            <td>Price</td>
                                            <td>Quantity</td>
                                            <td>SubTotal</td>
                                        </tr>
                                    @foreach($order->products as $product)
                                                <tr>
                                                    <td>{{ $product->name }} -
                                                        @if(isset($product->attributeCombinations))
                                                            @foreach ($product->attributeCombinations as $combination)
                                                                @if($combination->id == $product->pivot->product_attribute_id)
                                                                    {{$combination->all_combination}}
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>{{ currency_with_format($product->pivot->unit_price) }}</td>
                                                    <td>{{ $product->pivot->order_quantity }}</td>
                                                    <td>{{ currency_with_format($product->pivot->subtotal) }}</td>
                                                </tr>
                                        @endforeach
                                    </table>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>



