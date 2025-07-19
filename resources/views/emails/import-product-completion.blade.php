@component('mail::message')
# Hello {{ $details['name'] }}

Import Product successfully. you can visit product page for confirmation.

{{$details['url']}}
Thanks
@endcomponent
