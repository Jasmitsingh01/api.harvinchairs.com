<p>Hello {{ (isset($user) && !empty($user)) ? $user->name : $name }},</p>

<p>Your Two-Factor Authentication Code is:</p>

<div style="border: 1px solid #ccc; padding: 10px; font-size: 24px; text-align: center;">
    <strong>{{ (isset($user) && $user->two_factor_code) ? $user->two_factor_code : $otp }}</strong>
</div>

<p>
    This code will expire in
    @php
        if(isset($user) && $user->two_factor_expires_at){
            $expiresInMinutes = now()->diffInMinutes($user->two_factor_expires_at);
            echo $expiresInMinutes;
        }else{
            echo $expiresInMinutes;
        }
    @endphp
    minutes.
</p>

<p>If you did not request this code, please ignore this email.</p>

<p>Thank you for using our service.</p>

<p>Sincerely,<br>Your Application Team</p>

