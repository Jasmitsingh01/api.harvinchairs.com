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
            <td>Thank you for shopping with {{config('app.name')}}</td>
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
                                    ORDER {{ $order->tracking_number }} - PROCESSING</h4>
                                <p style="margin:5px 2px;background:#d5d5d5;height:1px">
                                </p>
                                <p style="padding-bottom:2px"> Your order with the
                                    reference {{ $order->tracking_number }} has been processing.
                                    <br>
                                    @if($order->orderCarrier && isset($order->orderCarrier->url) && isset($order->orderCarrier->tracking_number))
                                        Your Tracking number is <b>{{$order->orderCarrier->tracking_number}}</b>. You can track your package using the following link <a href="{{$order->orderCarrier->url}}">{{$order->orderCarrier->url}}</a>
                                    @endif
                                </p>
                            </td>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
        <tr>
            <td align="center">
                <table width="94%" cellspacing="0" cellpadding="5">
                <tbody>
                    <tr>
                        <td>
                            <br>
                            You can review your Order from my account section by clicking <a href="{{$url}}">"My Orders"</a>
                        </td>
                    </tr>
                </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
