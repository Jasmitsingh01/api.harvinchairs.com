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
            <td>We are writing to confirm that we have received your request to cancel your order #{{$order->tracking_number}} placed on {{getFormatedDateTime($order->created_at)}}. </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>We understand that plans change, and we’re here to support your needs.</td>
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
                                    Here are the details of your canceled order for your records:</h4>
                                <p style="margin:5px 2px;background:#d5d5d5;height:1px">
                                </p>
                                <p style="padding-bottom:2px">
                                    Order Number: {{$order->tracking_number}} <br/>
                                    Order Date: {{getFormatedDateTime($order->created_at)}}<br/>
                                    Total Amount: {{currency_with_format($order->paid_total)}}<br/>
                                    Cacellation Date: {{getFormatedDateTime($order->cancellation_date)}}
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
    </tbody>
</table>



