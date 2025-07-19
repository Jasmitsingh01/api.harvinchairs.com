<?php


namespace App\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Facades\Shop;

class PaymentIntentQuery
{
    public function getPaymentIntent($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('App\Http\Controllers\PaymentIntentController@getPaymentIntent', $args);
    }
}
