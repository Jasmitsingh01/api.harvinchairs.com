{{--$review collection is available here--}}


A new rating has been given<br/>

Comments: {{ $review->comment }}<br>
Ratings: {{ $review->rating }}<br/>

<button href="{{$url}}">View {{$product->name}}</button>
<br/>
Thanks,<br>
{{ config('app.name') }}
