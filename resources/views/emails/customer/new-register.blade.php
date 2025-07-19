<table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
    <tbody>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>Hello {{ $user->name }},</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>Welcome to Harvin, Thank you for creating an account and the priceless belief you have in us.</td>
        </tr>
        <tr>
            <td></td>
        </tr>

        <tr>
            <td>
                <table style="width:100%;text-align:center">
                    <tr>
                        <td></td>
                        <td><img src="{{config('app.url')}}/images/hand-thumbnail.png" width="100px" height="100px"/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><h2>Thank You!</h2></td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <strong>Important Security Tips:</strong>
            </td>
        </tr>
        <tr>
            <td>
                <ul>
                    <li>1. Always Keep your account details safe. </li>
                    <li>2. Never disclose your login details to anyone. </li>
                    <li>3. Change your password regularly. </li>
                    <li>4. If you suspect someone is using your account illegally, please notify us immediately. </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td>Now you can place orders on our shop : <a href="{{ $url }}">{{$app_name}}</a></td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>
